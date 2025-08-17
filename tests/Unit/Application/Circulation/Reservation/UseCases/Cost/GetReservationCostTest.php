<?php

namespace Tests\Unit\Application\Circulation\Reservation\UseCases\Cost;

use App\Application\Circulation\Reservation\Exception\ReservationNotFoundException;
use App\Application\Circulation\Reservation\UseCases\Cost\GetReservationCost;
use App\Application\Circulation\Reservation\UseCases\Shared\Service\Find\FindOrFailReservationByIdService;
use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Circulation\Reservation\Status\Implementation\Pending;
use App\Domain\Shared\ValueObject\Id;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\PhpUnit\ClockMock;

class GetReservationCostTest extends TestCase
{
    private FindOrFailReservationByIdService|MockObject $findOrFailReservationByIdService;

    private GetReservationCost $getReservationCost;

    protected function setUp(): void
    {
        ClockMock::register(\DateTimeImmutable::class);

        ClockMock::withClockMock(strtotime('2026-08-16'));

        $this->findOrFailReservationByIdService = $this->createMock(
            FindOrFailReservationByIdService::class
        );

        $this->getReservationCost = new GetReservationCost($this->findOrFailReservationByIdService);
    }

    /**
     * @throws ReservationNotFoundException
     */
    #[Test]
    public function itShouldReturnReservationCalculateCostWhenReservationHasNotYetBeenReturned(): void
    {
        $id = new Id(rand(1, 1999));

        $reservation = new Reservation(
            userId: new Id(rand(1, 1999)),
            storedBookId: new Id(rand(1, 1999)),
            status: new Pending,
            reservedAt: new \DateTimeImmutable('2025-08-14'),
            id: $id
        );

        $this->findOrFailReservationByIdService
            ->expects($this->once())
            ->method('findOrFail')
            ->with($id)
            ->willReturn($reservation);

        $cost = $this->getReservationCost->cost($id);

        $this->assertEquals('13.5', $cost->getReservationCost()->getAmount());
        $this->assertEquals('4.5', $cost->getCostPerDays()->getAmount());

        $this->assertEquals('R$ 13,50', $cost->getReservationCost()->getFormattedAmount());
        $this->assertEquals('R$ 4,50', $cost->getCostPerDays()->getFormattedAmount());

        $this->assertEquals($reservation, $cost->getReservation());
    }

    /**
     * @throws ReservationNotFoundException
     */
    #[Test]
    public function itShouldReturnReservationCalculateCostWhenReservationHasBeenReturned(): void
    {
        $id = new Id(rand(1, 1999));

        $reservation = new Reservation(
            userId: new Id(rand(1, 1999)),
            storedBookId: new Id(rand(1, 1999)),
            status: new Pending,
            reservedAt: new \DateTimeImmutable('2025-08-14'),
            returnedAt: new \DateTimeImmutable('2025-08-20'),
            id: $id
        );

        $this->findOrFailReservationByIdService
            ->expects($this->once())
            ->method('findOrFail')
            ->with($id)
            ->willReturn($reservation);

        $cost = $this->getReservationCost->cost($id);

        $this->assertEquals('27.0', $cost->getReservationCost()->getAmount());
        $this->assertEquals('4.5', $cost->getCostPerDays()->getAmount());

        $this->assertEquals('R$ 27,00', $cost->getReservationCost()->getFormattedAmount());
        $this->assertEquals('R$ 4,50', $cost->getCostPerDays()->getFormattedAmount());

        $this->assertEquals(6, $cost->getReservedDays());

        $this->assertEquals($reservation, $cost->getReservation());
    }

    #[Test]
    public function itShouldThrowReservationNotFoundExceptionWhenReservationDoesNotExist(): void
    {
        $reservationId = new Id(1);

        $this->findOrFailReservationByIdService
            ->expects($this->once())
            ->method('findOrFail')
            ->with($reservationId)
            ->willThrowException(ReservationNotFoundException::create());

        $this->expectException(ReservationNotFoundException::class);
        $this->expectExceptionMessage('Reservation not found');

        $this->getReservationCost->cost($reservationId);
    }

    protected function tearDown(): void
    {
        ClockMock::withClockMock(false);
    }
}
