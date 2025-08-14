<?php

declare(strict_types=1);

namespace App\Domain\Circulation\Reservation\Factory;

use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Circulation\Reservation\Status\Implementation\Pending;
use App\Domain\Shared\ValueObject\Id;

class DefaultReservationFactory implements ReservationFactoryInterface
{
    public function build(Id $userId, Id $storedBookId): Reservation
    {
        return new Reservation(
            userId: $userId,
            storedBookId: $storedBookId,
            status: new Pending,
            reservedAt: new \DateTimeImmutable
        );
    }
}
