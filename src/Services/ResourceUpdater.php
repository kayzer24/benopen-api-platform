<?php
declare(strict_types=1);

namespace App\Services;

use App\Authorization\AuthenticationCheckerInterface;
use App\Authorization\ResourceAccessCheckerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class ResourceUpdater implements ResourceUpdaterInterface
{
    protected array $methodAllowed = [
        Request::METHOD_PUT,
        Request::METHOD_PATCH,
        Request::METHOD_DELETE,
    ];

    public function __construct(
        private ResourceAccessCheckerInterface $resourceAccessChecker,
        private AuthenticationCheckerInterface $authenticationChecker
    ) {}

    public function process(string $method, UserInterface $user): bool
    {
        if (in_array($method, $this->methodAllowed, true)) {
            $this->authenticationChecker->isAuthenticated();
            $this->resourceAccessChecker->canAccess($user->getId());

            return true;
        }

        return false;
    }
}