<?php

namespace App\Infrastructure\Persistence\Repository\Inventory\StoredBook\Find;

use App\Domain\Inventory\StoredBook\Repository\Find\FindStoredBookByIdRepositoryInterface;
use App\Domain\Inventory\StoredBook\StoredBook;
use App\Domain\Shared\ValueObject\Id;
use App\Infrastructure\Persistence\Mappers\Inventory\StoredBook\StoredBookMapper;
use App\Infrastructure\Persistence\Repository\Inventory\StoredBook\BaseStoreBookRepository;

final class EloquentFindStoredBookByIdRepository extends BaseStoreBookRepository implements FindStoredBookByIdRepositoryInterface
{
    public function find(Id $id): ?StoredBook
    {
        $model = $this->model->find($id->getValue());

        return empty($model) ? null : StoredBookMapper::toDomainEntity($model);
    }
}
