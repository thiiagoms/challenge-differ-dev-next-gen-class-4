<?php

namespace Tests\Unit\Domain\Circulation\Reservation;

use App\Domain\Circulation\Reservation\Factory\ReservationFactory;
use App\Domain\Circulation\Reservation\Status\Exception\InvalidReservationStatusTransitionException;
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

        $reservation = ReservationFactory::build(
            userId: $userId,
            storedBookId: $storedBookId,
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
        $reservation = ReservationFactory::build(
            userId: new Id(rand(1, 1999)),
            storedBookId: new Id(rand(1, 1999)),
        );

        $reservation->return();

        $this->assertEquals(Status::RETURNED, $reservation->getStatus());
        $this->assertNotEquals(Status::PENDING, $reservation->getStatus());
    }

    #[Test]
    public function itShouldThrowExceptionWhenReservationIsAlreadyReturned(): void
    {
        $reservation = ReservationFactory::build(
            userId: new Id(rand(1, 1999)),
            storedBookId: new Id(rand(1, 1999)),
        );

        $reservation->return();

        $this->expectException(InvalidReservationStatusTransitionException::class);
        $this->expectExceptionMessage(
            "Invalid reservation status transition from 'returned' to 'returned' for reservation"
        );

        $reservation->return();
    }
}
