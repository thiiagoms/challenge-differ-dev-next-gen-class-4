<?php

namespace Tests\Unit\Domain\Circulation\Reservation;

use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Circulation\Reservation\Status\Exception\InvalidReservationStatusTransitionException;
use App\Domain\Circulation\Reservation\Status\Implementation\Pending;
use App\Domain\Circulation\Reservation\Status\Status;
use App\Domain\Shared\ValueObject\Id;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ReservationTest extends TestCase
{
    #[Test]
    public function itShouldCreateReservation(): void
    {
        $userId = new Id(rand(1, 1999));
        $storedBookId = new Id(rand(1, 1999));

        $reservation = new Reservation(
            userId: $userId,
            storedBookId: $storedBookId,
            status: new Pending,
            reservedAt: new \DateTimeImmutable
        );

        $this->assertEquals($userId, $reservation->getUserId());
        $this->assertEquals($storedBookId, $reservation->getStoredBookId());

        $this->assertEquals(Status::PENDING, $reservation->getStatus());
        $this->assertEquals(
            (new \DateTimeImmutable)->format('Y-m-d H:i:s'),
            $reservation->getReservedAt()->format('Y-m-d H:i:s')
        );
    }

    #[Test]
    public function itShouldAllowReturnReservationWithPendingStatus(): void
    {
        $reservation = new Reservation(
            userId: new Id(rand(1, 1999)),
            storedBookId: new Id(rand(1, 1999)),
            status: new Pending,
            reservedAt: new \DateTimeImmutable
        );

        $reservation->return();

        $this->assertEquals(Status::RETURNED, $reservation->getStatus());
        $this->assertNotEquals(Status::PENDING, $reservation->getStatus());
    }

    #[Test]
    public function itShouldThrowExceptionWhenReservationIsAlreadyReturned(): void
    {
        $reservation = new Reservation(
            userId: new Id(rand(1, 1999)),
            storedBookId: new Id(rand(1, 1999)),
            status: new Pending,
            reservedAt: new \DateTimeImmutable
        );

        $reservation->return();

        $this->expectException(InvalidReservationStatusTransitionException::class);
        $this->expectExceptionMessage(
            "Invalid reservation status transition from 'returned' to 'returned' for reservation"
        );

        $reservation->return();
    }
}
