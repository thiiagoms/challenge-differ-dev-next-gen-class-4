<?php

namespace App\Domain\Circulation\Reservation\Repository\Update;

use App\Domain\Circulation\Reservation\Reservation;

interface UpdateReservationRepositoryInterface
{
    public function update(Reservation $reservation): bool;
}
