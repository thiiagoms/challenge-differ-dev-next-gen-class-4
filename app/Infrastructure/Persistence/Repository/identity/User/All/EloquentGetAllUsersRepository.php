<?php

namespace App\Infrastructure\Persistence\Repository\identity\User\All;

use App\Domain\Identity\User\Repository\All\ReturnAllUsersRepositoryInterface;
use App\Domain\Identity\User\User as UserEntity;
use App\Infrastructure\Persistence\Mappers\Identity\User\UserMapper;
use App\Infrastructure\Persistence\Models\User as LaravelUserModel;
use App\Infrastructure\Persistence\Repository\identity\User\BaseUserRepository;

class EloquentGetAllUsersRepository extends BaseUserRepository implements ReturnAllUsersRepositoryInterface
{
    public function all(): array
    {
        $users = $this->model->all();

        if (empty($users)) {
            return [];
        }

        return array_map(
            fn (LaravelUserModel $user): UserEntity => UserMapper::toDomainEntity($user),
            $users->all()
        );
    }
}
