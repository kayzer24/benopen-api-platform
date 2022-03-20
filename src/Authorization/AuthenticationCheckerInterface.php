<?php

declare(strict_types=1);

namespace App\Authorization;

interface AuthenticationCheckerInterface
{
    public const MESSAGE_ERROR = 'Your are not authenticated';

    public function isAuthenticated(): void;
}
