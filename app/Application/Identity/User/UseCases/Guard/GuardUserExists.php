<?php

declare(strict_types=1);

namespace App\Application\Identity\User\UseCases\Guard;

use App\Application\Identity\User\Exception\UserNotFoundException;
use App\Domain\Identity\User\Repository\Find\FindUserByIdRepositoryInterface;
use App\Domain\Shared\ValueObject\Id;

class GuardUserExists
{
    public function __construct(private readonly FindUserByIdRepositoryInterface $repository) {}

    /**
     * @throws UserNotFoundException
     */
    public function verify(Id $id): void
    {
        $user = $this->repository->find($id);

        if (empty($user)) {
            throw UserNotFoundException::create();
        }
    }
}
