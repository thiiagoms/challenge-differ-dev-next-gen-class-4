<?php

namespace Tests\Unit\Application\Circulation\Reservation\UseCases\Return;

use App\Application\Circulation\Reservation\Exception\ReservationNotFoundException;
use App\Application\Circulation\Reservation\UseCases\Return\DTO\ReturnReservationDTO;
use App\Application\Circulation\Reservation\UseCases\Return\ReturnReservation;
use App\Application\Circulation\Reservation\UseCases\Shared\Service\Find\FindOrFailReservationByIdService;
use App\Domain\Circulation\Reservation\Exception\ReturnReservationDateIsInvalidException;
use App\Domain\Circulation\Reservation\Repository\Update\UpdateReservationRepositoryInterface;
use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Circulation\Reservation\Status\Exception\InvalidReservationStatusTransitionException;
use App\Domain\Circulation\Reservation\Status\Implementation\Pending;
use App\Domain\Circulation\Reservation\Status\Implementation\Returned;
use App\Domain\Circulation\Reservation\Status\Status;
use App\Domain\Shared\ValueObject\Id;
use DateMalformedStringException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ReturnReservationTest extends TestCase
{
    private FindOrFailReservationByIdService|MockObject $findOrFailReservationByIdService;

    private UpdateReservationRepositoryInterface|MockObject $repository;

    private ReturnReservation $returnReservation;

    protected function setUp(): void
    {
        $this->findOrFailReservationByIdService = $this->createMock(FindOrFailReservationByIdService::class);

        $this->repository = $this->createMock(UpdateReservationRepositoryInterface::class);

        $this->returnReservation = new ReturnReservation(
            findOrFailReservationByIdService: $this->findOrFailReservationByIdService,
            repository: $this->repository
        );
    }

    /**
     * @throws ReturnReservationDateIsInvalidException
     * @throws ReservationNotFoundException
     * @throws InvalidReservationStatusTransitionException
     * @throws DateMalformedStringException
     */
    #[Test]
    public function itShouldUpdateReservationStatusToReturned(): void
    {
        $dto = new ReturnReservationDTO(
            id: new Id(302, 400),
            returnDate: (new \DateTimeImmutable)->modify('+3 days')
        );

        $this->findOrFailReservationByIdService
            ->expects($this->once())
            ->method('findOrFail')
            ->with($dto->getId())
            ->willReturn(
                new Reservation(
                    userId: new Id(rand(100, 101)),
                    storedBookId: new Id(rand(102, 301)),
                    status: new Pending,
                    reservedAt: new \DateTimeImmutable,
                    returnedAt: null,
                    id: $dto->getId()
                )
            );

        $this->repository
            ->expects($this->once())
            ->method('update')
            ->with($this->isInstanceOf(Reservation::class))
            ->willReturn(true);

        $reservation = $this->returnReservation->complete($dto);

        $this->assertEquals($dto->getId(), $reservation->getId());

        $this->assertEquals(Status::RETURNED, $reservation->getStatus());
        $this->assertEquals($dto->getReturnDate(), $reservation->getReturnedAt());
        $this->assertNotNull($reservation->getReservedAt());

        $this->assertNotEquals(Status::PENDING, $reservation->getStatus());
    }

    /**
     * @throws DateMalformedStringException
     * @throws ReturnReservationDateIsInvalidException
     * @throws InvalidReservationStatusTransitionException
     */
    #[Test]
    public function itShouldThrowExceptionWhenReservationNotFound(): void
    {
        $dto = new ReturnReservationDTO(
            id: new Id(302, 400),
            returnDate: (new \DateTimeImmutable)->modify('+3 days')
        );

        $this->findOrFailReservationByIdService
            ->expects($this->once())
            ->method('findOrFail')
            ->with($dto->getId())
            ->willThrowException(ReservationNotFoundException::create());

        $this->repository
            ->expects($this->never())
            ->method('update');

        $this->expectException(ReservationNotFoundException::class);
        $this->expectExceptionMessage('Reservation not found');

        $this->returnReservation->complete($dto);
    }

    /**
     * @throws DateMalformedStringException
     * @throws ReservationNotFoundException
     * @throws InvalidReservationStatusTransitionException
     */
    #[Test]
    public function itShouldThrowExceptionWhenReturnDateIsInvalid(): void
    {
        $dto = new ReturnReservationDTO(
            id: new Id(302, 400),
            returnDate: (new \DateTimeImmutable)->modify('-3 days')
        );

        $this->findOrFailReservationByIdService
            ->expects($this->once())
            ->method('findOrFail')
            ->with($dto->getId())
            ->willReturn(
                new Reservation(
                    userId: new Id(rand(100, 101)),
                    storedBookId: new Id(rand(102, 301)),
                    status: new Pending,
                    reservedAt: new \DateTimeImmutable,
                    returnedAt: null,
                    id: $dto->getId()
                )
            );

        $this->repository
            ->expects($this->never())
            ->method('update');

        $this->expectException(ReturnReservationDateIsInvalidException::class);
        $this->expectExceptionMessage('Return date must be greater than reserved date');

        $this->returnReservation->complete($dto);
    }

    /**
     * @throws DateMalformedStringException
     * @throws ReservationNotFoundException
     * @throws ReturnReservationDateIsInvalidException
     */
    #[Test]
    public function itShouldThrowExceptionWhenReservationIsAlreadyReturned(): void
    {
        $dto = new ReturnReservationDTO(
            id: new Id(302, 400),
            returnDate: (new \DateTimeImmutable)->modify('+3 days')
        );

        $this->findOrFailReservationByIdService
            ->expects($this->once())
            ->method('findOrFail')
            ->with($dto->getId())
            ->willReturn(
                new Reservation(
                    userId: new Id(rand(100, 101)),
                    storedBookId: new Id(rand(102, 301)),
                    status: new Returned,
                    reservedAt: new \DateTimeImmutable,
                    returnedAt: (new \DateTimeImmutable)->modify('+3 days'),
                    id: $dto->getId()
                )
            );

        $this->repository
            ->expects($this->never())
            ->method('update');

        $this->expectException(InvalidReservationStatusTransitionException::class);
        $this->expectExceptionMessage('Reservation already returned');

        $this->returnReservation->complete($dto);
    }

    /**
     * @throws DateMalformedStringException
     * @throws ReservationNotFoundException
     * @throws ReturnReservationDateIsInvalidException
     * @throws InvalidReservationStatusTransitionException
     */
    #[Test]
    public function itShouldThrowExceptionWhenRepositoryFailsToUpdateReservation(): void
    {
        $dto = new ReturnReservationDTO(
            id: new Id(302, 400),
            returnDate: (new \DateTimeImmutable)->modify('+3 days')
        );

        $this->findOrFailReservationByIdService
            ->expects($this->once())
            ->method('findOrFail')
            ->with($dto->getId())
            ->willReturn(
                new Reservation(
                    userId: new Id(rand(100, 101)),
                    storedBookId: new Id(rand(102, 301)),
                    status: new Pending,
                    reservedAt: new \DateTimeImmutable,
                    returnedAt: null,
                    id: $dto->getId()
                )
            );

        $this->repository
            ->expects($this->once())
            ->method('update')
            ->with($this->isInstanceOf(Reservation::class))
            ->willThrowException(new \Exception('Failed to update reservation in the database'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to update reservation in the database');

        $reservation = $this->returnReservation->complete($dto);
    }
}
