<?php

namespace Tests\Unit\Domain\Circulation\Reservation;

use App\Application\Circulation\Reservation\UseCases\Shared\Const\Rate;
use App\Domain\Circulation\Reservation\Exception\ReturnReservationDateIsInvalidException;
use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Circulation\Reservation\Status\Exception\InvalidReservationStatusTransitionException;
use App\Domain\Circulation\Reservation\Status\Implementation\Pending;
use App\Domain\Circulation\Reservation\Status\Status;
use App\Domain\Shared\ValueObject\Currency;
use App\Domain\Shared\ValueObject\Id;
use App\Domain\Shared\ValueObject\Money;
use DateMalformedStringException;
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

    /**
     * @throws ReturnReservationDateIsInvalidException
     * @throws InvalidReservationStatusTransitionException
     * @throws DateMalformedStringException
     */
    #[Test]
    public function itShouldAllowReturnReservationWithPendingStatus(): void
    {
        $reservedAt = new \DateTimeImmutable;

        $reservation = new Reservation(
            userId: new Id(rand(1, 1999)),
            storedBookId: new Id(rand(1, 1999)),
            status: new Pending,
            reservedAt: $reservedAt
        );

        $returnedAtDate = $reservedAt->modify('+1 day');

        $reservation->return($returnedAtDate);

        $this->assertEquals(Status::RETURNED, $reservation->getStatus());
        $this->assertNotEquals(Status::PENDING, $reservation->getStatus());
    }

    /**
     * @throws ReturnReservationDateIsInvalidException
     * @throws InvalidReservationStatusTransitionException
     * @throws DateMalformedStringException
     */
    #[Test]
    public function itShouldThrowExceptionWhenReturnDateIsBeforeReservedDate(): void
    {
        $reservedAt = new \DateTimeImmutable;

        $reservation = new Reservation(
            userId: new Id(rand(1, 1999)),
            storedBookId: new Id(rand(1, 1999)),
            status: new Pending,
            reservedAt: $reservedAt
        );

        $returnedAtDate = $reservedAt->modify('-1 day');

        $this->expectException(ReturnReservationDateIsInvalidException::class);
        $this->expectExceptionMessage('Return date must be greater than reserved date');

        $reservation->return($returnedAtDate);
    }

    /**
     * @throws ReturnReservationDateIsInvalidException
     * @throws InvalidReservationStatusTransitionException
     * @throws DateMalformedStringException
     */
    #[Test]
    public function itShouldThrowExceptionWhenReservationIsAlreadyReturned(): void
    {
        $reservedAt = new \DateTimeImmutable;

        $reservation = new Reservation(
            userId: new Id(rand(1, 1999)),
            storedBookId: new Id(rand(1, 1999)),
            status: new Pending,
            reservedAt: $reservedAt
        );

        $returnedAtDate = $reservedAt->modify('+1 day');

        $reservation->return($returnedAtDate);

        $this->expectException(InvalidReservationStatusTransitionException::class);
        $this->expectExceptionMessage('Reservation already returne');

        $reservation->return($returnedAtDate);
    }

    #[Test]
    public function itShouldCalculateTheDailyRateForAReservationThatHasNotYetBeenReturned(): void
    {
        $reservation = new Reservation(
            userId: new Id(rand(1, 1999)),
            storedBookId: new Id(rand(1, 1999)),
            status: new Pending,
            reservedAt: new \DateTimeImmutable('2025-08-14')
        );

        $tax = new Money(amount: Rate::DAILY_RATE_TAX, currency: Currency::BRL);

        $calculationDate = new \DateTimeImmutable('2025-08-16');

        $reservedDays = $reservation->getReservedDays(calculationDate: $calculationDate);

        $dailyRate = $reservation->calculateDailyRate(tax: $tax, calculationDate: $calculationDate);

        $this->assertEquals(2, $reservedDays);
        $this->assertEquals(9.0, $dailyRate->getAmount());
        $this->assertEquals('BRL', $dailyRate->getCurrency()->value);
        $this->assertEquals('R$ 9,00', $dailyRate->getFormattedAmount());
    }

    #[Test]
    public function itShouldCalculateTheDailyRateForAReservationThatHasAlreadyBeenReturned(): void
    {
        $reservation = new Reservation(
            userId: new Id(rand(1, 1999)),
            storedBookId: new Id(rand(1, 1999)),
            status: new Pending,
            reservedAt: new \DateTimeImmutable('2025-08-14'),
            returnedAt: new \DateTimeImmutable('2025-08-20')
        );

        $tax = new Money(amount: Rate::DAILY_RATE_TAX, currency: Currency::BRL);

        $reservedDays = $reservation->getReservedDays();

        $dailyRate = $reservation->calculateDailyRate(tax: $tax);

        $this->assertEquals(6, $reservedDays);
        $this->assertEquals(27.0, $dailyRate->getAmount());
        $this->assertEquals('BRL', $dailyRate->getCurrency()->value);
        $this->assertEquals('R$ 27,00', $dailyRate->getFormattedAmount());
    }
}
