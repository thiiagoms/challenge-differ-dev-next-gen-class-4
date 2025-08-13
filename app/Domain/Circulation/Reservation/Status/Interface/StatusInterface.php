<?php

namespace App\Domain\Circulation\Reservation\Status\Interface;

use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Circulation\Reservation\Status\Status;

interface StatusInterface
{
    public function pending(Reservation $reservation): void;

    public function returned(Reservation $reservation): void;

    public function getStatus(): Status;
}
