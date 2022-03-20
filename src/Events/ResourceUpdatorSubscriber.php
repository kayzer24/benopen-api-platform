<?php
declare(strict_types=1);


namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Article;
use App\Entity\User;
use App\Services\ResourceUpdaterInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ResourceUpdatorSubscriber implements EventSubscriberInterface
{
    public function __construct(private ResourceUpdaterInterface $resourceUpdater)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['check', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function check(ViewEvent $event): void
    {
        $object = $event->getControllerResult();

        if ($object instanceof User || $object instanceof Article) {
            $user = $object instanceof User ? $object : $object->getAuthor();

            $canProcess = $this->resourceUpdater->process(
                $event->getRequest()->getMethod(),
                $user
            );

            if ($canProcess) {
                $user->setUpdatedAt(new \DateTimeImmutable());
            }
        }
    }
}