<?php

declare(strict_types=1);

namespace App\Authorization;

use App\Exceptions\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationChecker implements AuthenticationCheckerInterface
{
    private ?UserInterface $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    public function isAuthenticated(): void
    {
        if (null === $this->user) {
            throw new AuthenticationException(
                Response::HTTP_UNAUTHORIZED,
                AuthenticationCheckerInterface::MESSAGE_ERROR
            );
        }
    }
}
