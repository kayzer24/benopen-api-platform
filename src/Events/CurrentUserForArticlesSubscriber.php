<?php
declare(strict_types=1);

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Article;
use App\Entity\User;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class CurrentUserForArticlesSubscriber implements EventSubscriberInterface
{

    public function __construct(private Security $security)
    {
    }

    public function currentUserForArticle(ViewEvent $event): void
    {
        $article = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($article instanceof Article && Request::METHOD_POST === $method) {
            /** @var User $user */
            $user = $this->security->getUser();

            $article->setAuthor($user);
        }
    }

    #[ArrayShape([KernelEvents::VIEW => "array"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['currentUserForArticle', EventPriorities::PRE_VALIDATE],
        ];
    }
}