<?php

namespace App\Domain\Circulation\Reservation\Status;

enum Status: string
{
    case PENDING = 'pending';
    case RETURNED = 'returned';

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isReturned(): bool
    {
        return $this === self::RETURNED;
    }
}
