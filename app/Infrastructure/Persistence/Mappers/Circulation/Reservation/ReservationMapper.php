<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Mappers\Circulation\Reservation;

use App\Domain\Circulation\Reservation\Reservation as ReservationEntity;
use App\Domain\Circulation\Reservation\Status\Implementation\Pending;
use App\Domain\Circulation\Reservation\Status\Implementation\Returned;
use App\Domain\Shared\ValueObject\Id;
use App\Infrastructure\Persistence\Models\Reservation as LaravelReservationModel;
use DateMalformedStringException;
use DateTimeImmutable;

abstract class ReservationMapper
{
    /**
     * @throws DateMalformedStringException
     */
    public static function toDomainEntity(LaravelReservationModel $model): ReservationEntity
    {
        $status = $model->returned_at === null ? new Pending : new Returned;

        return new ReservationEntity(
            userId: new Id($model->user_id),
            storedBookId: new Id($model->stored_book_id),
            status: $status,
            reservedAt: new DateTimeImmutable($model->reserved_at),
            returnedAt: $model->returned_at ? new DateTimeImmutable($model->returned_at) : null,
            id: new Id($model->id),
            createdAt: $model->created_at->toDateTimeImmutable(),
            updatedAt: $model->updated_at->toDateTimeImmutable()
        );
    }

    public static function toPersistenceModel(ReservationEntity $entity): array
    {
        return [
            'id' => $entity->getId()?->getValue(),
            'user_id' => $entity->getUserId()->getValue(),
            'stored_book_id' => $entity->getStoredBookId()->getValue(),
            'reserved_at' => $entity->getReservedAt()->format('Y-m-d H:i:s'),
            'returned_at' => $entity->getReturnedAt()?->format('Y-m-d H:i:s'),
            'created_at' => $entity->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $entity->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
