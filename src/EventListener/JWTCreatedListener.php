<?php
declare(strict_types=1);

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTCreatedListener
{
    private UserInterface $user;

    public function __construct(private Security $security)
    {
        $this->user = $this->security->getUser();
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $payload = $event->getData();
        $payload['createdAt'] = $this->user->getCreatedAt();
        $event->setData($payload);
    }
}