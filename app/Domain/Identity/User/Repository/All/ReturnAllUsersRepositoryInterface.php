<?php

namespace App\Domain\Identity\User\Repository\All;

use App\Domain\Identity\User\User;

interface ReturnAllUsersRepositoryInterface
{
    /**
     * @return User[]
     */
    public function all(): array;
}
