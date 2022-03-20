<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\Security\Core\User\UserInterface;

interface ResourceUpdaterInterface
{
    public function process(string $method, UserInterface $user): bool;
}
