<?php

namespace App\Infrastructure\Persistence\Repository\Circulation\Reservation\Find;

use App\Domain\Circulation\Reservation\Repository\Find\FindReservationByIdRepositoryInterface;
use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Shared\ValueObject\Id;
use App\Infrastructure\Persistence\Mappers\Circulation\Reservation\ReservationMapper;
use App\Infrastructure\Persistence\Repository\Circulation\Reservation\BaseReservationRepository;

class EloquentFindReservationByIdRepository extends BaseReservationRepository implements FindReservationByIdRepositoryInterface
{
    /**
     * @throws \DateMalformedStringException
     */
    public function find(Id $id): ?Reservation
    {
        $reservation = $this->model->find($id->getValue());

        return empty($reservation) ? null : ReservationMapper::toDomainEntity($reservation);
    }
}
