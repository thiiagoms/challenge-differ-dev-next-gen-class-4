<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository\Circulation\Reservation\Register;

use App\Domain\Circulation\Reservation\Repository\Register\RegisterReservationRepositoryInterface;
use App\Domain\Circulation\Reservation\Reservation;
use App\Infrastructure\Persistence\Mappers\Circulation\Reservation\ReservationMapper;
use App\Infrastructure\Persistence\Models\Reservation as LaravelReservationModel;
use App\Infrastructure\Persistence\Repository\Circulation\Reservation\BaseReservationRepository;

class EloquentRegisterReservationRepository extends BaseReservationRepository implements RegisterReservationRepositoryInterface
{
    /**
     * @throws \DateMalformedStringException
     */
    public function save(Reservation $reservation): Reservation
    {
        $data = ReservationMapper::toPersistenceModel($reservation);

        $model = LaravelReservationModel::create($data);

        return ReservationMapper::toDomainEntity($model);
    }
}
