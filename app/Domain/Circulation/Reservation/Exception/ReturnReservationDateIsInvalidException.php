<?php

namespace App\Domain\Circulation\Reservation\Exception;

use App\Application\Shared\Exception\ForbiddenException;

class ReturnReservationDateIsInvalidException extends ForbiddenException
{
    public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function create(): self
    {
        return new self(message: 'Return date must be greater than reserved date');
    }
}
