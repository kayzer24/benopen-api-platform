<?php
declare(strict_types=1);

namespace App\Events;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasherSubscriber implements EventSubscriberInterface
{

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function hashPassword(ViewEvent $event): void
    {
        $user = $event->getControllerResult();

        if ($user instanceof User) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
        }
    }

    #[ArrayShape([KernelEvents::VIEW => "array"])]
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['hashPassword', EventPriorities::PRE_WRITE],
        ];
    }
}