<?php

namespace Tests\Unit\Application\Circulation\Reservation\UseCases\Shared\Service\Find;

use App\Application\Circulation\Reservation\Exception\ReservationNotFoundException;
use App\Application\Circulation\Reservation\UseCases\Shared\Service\Find\FindOrFailReservationByIdService;
use App\Domain\Circulation\Reservation\Repository\Find\FindReservationByIdRepositoryInterface;
use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Circulation\Reservation\Status\Implementation\Pending;
use App\Domain\Shared\ValueObject\Id;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FindOrFailReservationByIdServiceTest extends TestCase
{
    private Id $id;

    private FindReservationByIdRepositoryInterface|MockObject $repository;

    private FindOrFailReservationByIdService $service;

    protected function setUp(): void
    {
        $this->id = new Id(rand(1, 1000));

        $this->repository = $this->createMock(FindReservationByIdRepositoryInterface::class);

        $this->service = new FindOrFailReservationByIdService($this->repository);
    }

    /**
     * @throws ReservationNotFoundException
     */
    #[Test]
    public function itShouldReturnReservationWhenExistsInDatabase(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($this->id)
            ->willReturn(
                new Reservation(
                    userId: new Id(rand(100, 101)),
                    storedBookId: new Id(rand(102, 110)),
                    status: new Pending,
                    reservedAt: new \DateTimeImmutable,
                    id: $this->id
                )
            );

        $reservation = $this->service->findOrFail($this->id);

        $this->assertEquals($this->id, $reservation->getId());
    }

    #[Test]
    public function itShouldThrowExceptionWhenReservationDoesNotExist(): void
    {
        $this->repository
            ->expects($this->once())
            ->method('find')
            ->with($this->id)
            ->willReturn(null);

        $this->expectException(ReservationNotFoundException::class);
        $this->expectExceptionMessage('Reservation not found');

        $this->service->findOrFail($this->id);
    }
}
