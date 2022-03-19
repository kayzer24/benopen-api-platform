<?php
declare(strict_types=1);


namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Authorization\ArticleAuthorizationChecker;
use App\Authorization\UserAuthorizationChecker;
use App\Entity\Article;
use App\Entity\User;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ArticleSubscriber implements EventSubscriberInterface
{
    private array $methodNotAllowed = [
        Request::METHOD_POST,
        Request::METHOD_GET
    ];

    public function __construct(private ArticleAuthorizationChecker $articleAuthorizationChecker)
    {
    }

    #[ArrayShape([KernelEvents::VIEW => "array"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['check', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function check(ViewEvent $event): void
    {
        $article = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($article instanceof Article && !in_array($method, $this->methodNotAllowed)) {
            $this->articleAuthorizationChecker->check($article, $method);
            $article->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}