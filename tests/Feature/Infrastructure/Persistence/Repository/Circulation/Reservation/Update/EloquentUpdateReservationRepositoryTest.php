<?php

declare(strict_types=1);

namespace Tests\Feature\Infrastructure\Persistence\Repository\Circulation\Reservation\Update;

use App\Domain\Circulation\Reservation\Reservation;
use App\Domain\Circulation\Reservation\Status\Implementation\Returned;
use App\Domain\Shared\ValueObject\Id;
use App\Infrastructure\Persistence\Mappers\Circulation\Reservation\ReservationMapper;
use App\Infrastructure\Persistence\Models\Reservation as LaravelReservationModel;
use App\Infrastructure\Persistence\Repository\Circulation\Reservation\Find\EloquentFindReservationByIdRepository;
use App\Infrastructure\Persistence\Repository\Circulation\Reservation\Update\EloquentUpdateReservationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EloquentUpdateReservationRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private EloquentFindReservationByIdRepository $findReservationByIdRepository;

    private EloquentUpdateReservationRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->findReservationByIdRepository = new EloquentFindReservationByIdRepository;

        $this->repository = new EloquentUpdateReservationRepository;
    }

    /**
     * @throws \DateMalformedStringException
     * @throws \Exception
     */
    #[Test]
    public function itShouldUpdateReservationStatusInDatabase(): void
    {
        $reservationModelInDatabase = LaravelReservationModel::factory()->createOne([
            'reserved_at' => (new \DateTimeImmutable)->format('Y-m-d H:i:s'),
        ]);

        $reservationParse = ReservationMapper::toDomainEntity($reservationModelInDatabase);

        $reservation = new Reservation(
            userId: $reservationParse->getUserId(),
            storedBookId: $reservationParse->getStoredBookId(),
            status: new Returned,
            reservedAt: $reservationParse->getReservedAt(),
            returnedAt: $reservationParse->getReservedAt()->modify('+3 days'),
            id: $reservationParse->getId()
        );

        $result = $this->repository->update($reservation);

        $this->assertTrue($result);

        $updatedReservation = $this->findReservationByIdRepository->find($reservation->getId());

        $this->assertEquals($reservation->getId(), $updatedReservation->getId());

        $this->assertEquals(
            $reservation->getReservedAt()->format('Y-m-d H:i:s'),
            $updatedReservation->getReservedAt()->format('Y-m-d H:i:s')
        );

        $this->assertEquals(
            $reservation->getUpdatedAt()->format('Y-m-d H:i:s'),
            $updatedReservation->getUpdatedAt()->format('Y-m-d H:i:s')
        );
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[Test]
    public function itShouldThrowExceptionWhenReservationIsNotPersistedInDatabase(): void
    {
        $reservation = new Reservation(
            userId: new Id(rand(1, 1000)),
            storedBookId: new Id(rand(1, 1000)),
            status: new Returned,
            reservedAt: new \DateTimeImmutable,
            returnedAt: (new \DateTimeImmutable)->modify('+3 days'),
            id: new Id(rand(1, 1000))
        );

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Failed to update reservation in the database');

        $this->repository->update($reservation);
    }
}
