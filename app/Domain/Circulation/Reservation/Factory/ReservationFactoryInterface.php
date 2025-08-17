<?php

declare(strict_types=1);

namespace App\Domain\Circulation\Reservation\Factory;

use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Shared\ValueObject\Id;

interface ReservationFactoryInterface
{
    public function build(Id $userId, Id $storedBookId): Reservation;
}
