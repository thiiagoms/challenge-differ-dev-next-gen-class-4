<?php

namespace App\Domain\Circulation\Reservation\Status\Implementation;

use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Circulation\Reservation\Status\Exception\InvalidReservationStatusTransitionException;
use App\Domain\Circulation\Reservation\Status\Interface\StatusInterface;
use App\Domain\Circulation\Reservation\Status\Status;

class Returned implements StatusInterface
{
    /**
     * @throws InvalidReservationStatusTransitionException
     */
    public function pending(Reservation $reservation): void
    {
        $message = sprintf(
            "Invalid reservation status transition from '%s' to '%s' for reservation",
            Status::RETURNED->value,
            Status::PENDING->value,
        );

        throw InvalidReservationStatusTransitionException::create($message);
    }

    public function returned(Reservation $reservation): void
    {
        throw InvalidReservationStatusTransitionException::create('Reservation already returned');
    }

    public function getStatus(): Status
    {
        return Status::RETURNED;
    }
}
