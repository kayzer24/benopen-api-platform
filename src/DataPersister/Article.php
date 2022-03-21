<?php

declare(strict_types=1);

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Article as ArticleEntity;
use App\Factory\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class Article implements ContextAwareDataPersisterInterface
{
    private ?UserInterface $user;

    public function __construct(
        private ContextAwareDataPersisterInterface $decorated,
        private MailerInterface $mailer,
        Security $security,
        private Email $email,
    ) {
        $this->user = $security->getUser();
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof ArticleEntity && $this->decorated->supports($data, $context);
    }

    public function persist($data, array $context = []): void
    {
        $this->decorated->persist($data, $context);

        if (
            (strtoupper($context['collection_operation_name']) ?? null) === Request::METHOD_POST
            && null !== $this->user
        ) {
            $email = $this->email->create(
                $this->user->getUserIdentifier(),
                'no-reply@miniblog.fr',
                'Article #' . $data->getId(),
                $data->getContent()
            );

            $this->mailer->send($email);
        }
    }

    public function remove($data, array $context = [])
    {
        return $this->decorated->remove($data, $context);
    }
}
