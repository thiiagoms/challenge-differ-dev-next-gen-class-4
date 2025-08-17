<?php

namespace App\Domain\Circulation\Reservation\Status\Interface;

use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Circulation\Reservation\Status\Exception\InvalidReservationStatusTransitionException;
use App\Domain\Circulation\Reservation\Status\Status;

interface StatusInterface
{
    /**
     * @throws InvalidReservationStatusTransitionException
     */
    public function pending(Reservation $reservation): void;

    /**
     * @throws InvalidReservationStatusTransitionException
     */
    public function returned(Reservation $reservation): void;

    public function getStatus(): Status;
}
