<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository\identity\User\Find;

use App\Domain\Identity\User\Repository\Find\FindUserByIdRepositoryInterface;
use App\Domain\Identity\User\User;
use App\Domain\Shared\ValueObject\Id;
use App\Infrastructure\Persistence\Repository\identity\User\BaseUserRepository;

final class EloquentFindUserByIdRepository extends BaseUserRepository implements FindUserByIdRepositoryInterface
{
    public function find(Id $id): ?User
    {
        $model = $this->model->find($id->getValue());

        return empty($model) ? null : UserMapper::toDomainEntity($model);
    }
}
