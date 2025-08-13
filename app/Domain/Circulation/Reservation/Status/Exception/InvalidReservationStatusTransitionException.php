<?php

namespace App\Domain\Circulation\Reservation\Status\Exception;

use App\Domain\Circulation\Reservation\Status\Status;

class InvalidReservationStatusTransitionException extends \DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function create(Status $from, Status $to): self
    {
        $message = sprintf(
            "Invalid reservation status transition from '%s' to '%s' for reservation.",
            $from->value,
            $to->value,
        );

        return new self($message);
    }
}
