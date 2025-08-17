<?php

namespace App\Domain\Circulation\Reservation\Repository\Find;

use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Shared\ValueObject\Id;

interface FindReservationByIdRepositoryInterface
{
    public function find(Id $id): ?Reservation;
}
