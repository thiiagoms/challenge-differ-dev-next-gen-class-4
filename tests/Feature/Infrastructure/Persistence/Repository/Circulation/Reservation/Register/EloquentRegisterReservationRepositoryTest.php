<?php

namespace Feature\Infrastructure\Persistence\Repository\Circulation\Reservation\Register;

use App\Domain\Circulation\Reservation\Factory\ReservationFactoryInterface;
use App\Domain\Circulation\Reservation\Status\Status;
use App\Domain\Shared\ValueObject\Id;
use App\Infrastructure\Persistence\Models\Reservation as LaravelReservationModel;
use App\Infrastructure\Persistence\Repository\Circulation\Reservation\Register\EloquentRegisterReservationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\Test;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\TestCase;

class EloquentRegisterReservationRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \DateMalformedStringException
     */
    #[Test]
    public function itShouldSaveReservationInDatabase(): void
    {
        $userId = new Id(rand(1, 100));
        $storedBookId = new Id(rand(101, 200));

        $reservation = $this->app
            ->get(ReservationFactoryInterface::class)
            ->build(
                userId: $userId,
                storedBookId: $storedBookId
            );

        $repository = new EloquentRegisterReservationRepository;

        $result = $repository->save($reservation);

        $this->assertEquals($userId->getValue(), $result->getUserId()->getValue());
        $this->assertEquals($storedBookId->getValue(), $result->getStoredBookId()->getValue());
        $this->assertEquals(Status::PENDING, $result->getStatus());

        $this->assertNotNull($result->getId());

        $reservationWasSaved = LaravelReservationModel::find($result->getId()->getValue());

        $this->assertNotEmpty($reservationWasSaved);
    }
}
