<?php

namespace Feature\Infrastructure\Persistence\Repository\Circulation\Reservation\Find;

use App\Domain\Shared\ValueObject\Id;
use App\Infrastructure\Persistence\Models\Reservation as LaravelReservationModel;
use App\Infrastructure\Persistence\Repository\Circulation\Reservation\Find\EloquentFindReservationByIdRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EloquentFindReservationByIdRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @throws \DateMalformedStringException
     */
    #[Test]
    public function itShouldReturnReservationWhenIdExistsInDatabase(): void
    {
        $id = new Id(rand(101, 10001));

        LaravelReservationModel::factory()->create(['id' => $id->getValue()]);

        $repository = new EloquentFindReservationByIdRepository;

        $reservation = $repository->find($id);

        $this->assertEquals($id->getValue(), $reservation->getId()->getValue());
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[Test]
    public function itShouldReturnNullWhenIdDoesNotExistInDatabase(): void
    {
        $id = new Id(rand(101, 10001));

        $repository = new EloquentFindReservationByIdRepository;

        $reservation = $repository->find($id);

        $this->assertNull($reservation);
    }
}
