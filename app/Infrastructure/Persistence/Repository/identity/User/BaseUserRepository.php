<?php

namespace App\Infrastructure\Persistence\Repository\identity\User;

use App\Infrastructure\Persistence\Models\User as LaravelUserModel;
use App\Infrastructure\Persistence\Repository\BaseRepository;

abstract class BaseUserRepository extends BaseRepository
{
    protected $model = LaravelUserModel::class;
}
