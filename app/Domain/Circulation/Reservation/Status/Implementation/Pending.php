<?php

namespace App\Domain\Circulation\Reservation\Status\Implementation;

use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Circulation\Reservation\Status\Exception\InvalidReservationStatusTransitionException;
use App\Domain\Circulation\Reservation\Status\Interface\StatusInterface;
use App\Domain\Circulation\Reservation\Status\Status;

class Pending implements StatusInterface
{
    public function pending(Reservation $reservation): void
    {
        throw InvalidReservationStatusTransitionException::create(
            from: Status::PENDING,
            to: Status::PENDING,
        );
    }

    public function returned(Reservation $reservation): void
    {
        $reservation->changeStatusTo(new Returned);
    }

    public function getStatus(): Status
    {
        return Status::PENDING;
    }
}
