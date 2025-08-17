<?php

namespace App\Application\Identity\User\UseCases\All;

use App\Domain\Identity\User\Repository\All\ReturnAllUsersRepositoryInterface;
use App\Domain\Identity\User\User;

final readonly class ReturnAllUsers
{
    public function __construct(private readonly ReturnAllUsersRepositoryInterface $repository) {}

    /**
     * @return User[]
     */
    public function all(): array
    {
        return $this->repository->all();
    }
}
