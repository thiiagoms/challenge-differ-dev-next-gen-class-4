<?php

namespace App\Domain\Circulation\Reservation\Status\Exception;

use App\Application\Shared\Exception\ForbiddenException;

class InvalidReservationStatusTransitionException extends ForbiddenException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function create(string $message): self
    {
        return new self(message: $message);
    }
}
