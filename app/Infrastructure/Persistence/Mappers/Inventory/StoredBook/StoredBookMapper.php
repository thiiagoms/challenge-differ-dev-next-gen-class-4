<?php

namespace App\Infrastructure\Persistence\Mappers\Inventory\StoredBook;

use App\Domain\Inventory\StoredBook\StoredBook as StoredBookEntity;
use App\Domain\Shared\ValueObject\Id;
use App\Infrastructure\Persistence\Models\StoredBook as LaravelStoredBookModel;

abstract class StoredBookMapper
{
    public static function toDomainEntity(LaravelStoredBookModel $model): StoredBookEntity
    {
        return new StoredBookEntity(
            bookId: new Id($model->book_id),
            id: new Id($model->id),
            createdAt: $model->created_at->toDateTimeImmutable(),
            updatedAt: $model->updated_at->toDateTimeImmutable()
        );
    }

    public static function toPersistenceModel(StoredBookEntity $entity): LaravelStoredBookModel
    {
        $model ??= new LaravelStoredBookModel;

        $model->id = empty($entity->getId()) ? null : $entity->getId()->getValue();
        $model->book_id = $entity->getBookId()->getValue();
        $model->created_at = Carbon::createFromImmutable($entity->getCreatedAt());
        $model->updated_at = Carbon::createFromImmutable($entity->getUpdatedAt());

        return $model;
    }
}
