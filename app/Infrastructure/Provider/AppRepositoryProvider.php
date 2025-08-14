<?php

namespace App\Infrastructure\Provider;

use App\Domain\Circulation\Reservation\Repository\Find\FindReservationByIdRepositoryInterface;
use App\Domain\Circulation\Reservation\Repository\Register\RegisterReservationRepositoryInterface;
use App\Domain\Identity\User\Repository\Find\FindUserByIdRepositoryInterface;
use App\Domain\Inventory\StoredBook\Repository\Find\FindStoredBookByIdRepositoryInterface;
use App\Infrastructure\Persistence\Repository\Circulation\Reservation\Find\EloquentFindReservationByIdRepository;
use App\Infrastructure\Persistence\Repository\Circulation\Reservation\Register\EloquentRegisterReservationRepository;
use App\Infrastructure\Persistence\Repository\identity\User\Find\EloquentFindUserByIdRepository;
use App\Infrastructure\Persistence\Repository\Inventory\StoredBook\Find\EloquentFindStoredBookByIdRepository;
use Illuminate\Support\ServiceProvider;

class AppRepositoryProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerIdentityRepositories();
        $this->registerInventoryRepositories();
        $this->registerCirculationRepositories();
    }

    private function registerIdentityRepositories(): void
    {
        $this->app->bind(
            abstract: FindUserByIdRepositoryInterface::class,
            concrete: EloquentFindUserByIdRepository::class
        );
    }

    private function registerInventoryRepositories(): void
    {
        $this->app->bind(
            abstract: FindStoredBookByIdRepositoryInterface::class,
            concrete: EloquentFindStoredBookByIdRepository::class
        );
    }

    private function registerCirculationRepositories(): void
    {
        $this->app->bind(
            abstract: RegisterReservationRepositoryInterface::class,
            concrete: EloquentRegisterReservationRepository::class
        );

        $this->app->bind(
            abstract: FindREservationByIdRepositoryInterface::class,
            concrete: EloquentFindReservationByIdRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
