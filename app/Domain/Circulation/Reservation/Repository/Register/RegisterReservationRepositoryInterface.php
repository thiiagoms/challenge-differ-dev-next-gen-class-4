<?php

namespace App\Domain\Circulation\Reservation\Repository\Register;

use App\Domain\Circulation\Reservation\Reservation;

interface RegisterReservationRepositoryInterface
{
    public function save(Reservation $reservation): Reservation;
}
