<?php
declare(strict_types=1);


namespace App\Authorization;

interface ResourceAccessCheckerInterface
{
    const MESSAGE_ERROR = "You are not authorized to edit this resource.";

    public function canAccess(?int $id): void;
}