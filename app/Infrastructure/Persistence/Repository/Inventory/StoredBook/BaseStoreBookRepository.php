<?php

namespace App\Infrastructure\Persistence\Repository\Inventory\StoredBook;

use App\Infrastructure\Persistence\Models\StoredBook as LaravelStoredBook;
use App\Infrastructure\Persistence\Repository\BaseRepository;

abstract class BaseStoreBookRepository extends BaseRepository
{
    protected $model = LaravelStoredBook::class;
}
