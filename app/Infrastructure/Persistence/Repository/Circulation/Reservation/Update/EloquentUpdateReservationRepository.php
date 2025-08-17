<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository\Circulation\Reservation\Update;

use App\Domain\Circulation\Reservation\Repository\Update\UpdateReservationRepositoryInterface;
use App\Domain\Circulation\Reservation\Reservation;
use App\Infrastructure\Persistence\Mappers\Circulation\Reservation\ReservationMapper;
use App\Infrastructure\Persistence\Repository\Circulation\Reservation\BaseReservationRepository;
use Illuminate\Support\Facades\DB;

class EloquentUpdateReservationRepository extends BaseReservationRepository implements UpdateReservationRepositoryInterface
{
    /**
     * @throws \Exception
     */
    public function update(Reservation $reservation): bool
    {
        if ($reservation->getId() === null) {
            throw new \InvalidArgumentException('Reservation ID cannot be null for return operation.');
        }

        $result = (bool) DB::table('reservations')
            ->where('id', $reservation->getId()->getValue())
            ->update(ReservationMapper::toPersistenceModel($reservation));

        if (! $result) {
            throw new \Exception('Failed to update reservation in the database');
        }

        return true;
    }
}
