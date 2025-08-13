<?php

namespace App\Infrastructure\Provider;

use App\Domain\Identity\User\Repository\Find\FindUserByIdRepositoryInterface;
use App\Infrastructure\Persistence\Repository\identity\User\Find\EloquentFindUserByIdRepository;
use Illuminate\Support\ServiceProvider;

class AppRepositoryProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerIdentityRepositories();
    }

    private function registerIdentityRepositories(): void
    {
        $this->app->bind(
            abstract: FindUserByIdRepositoryInterface::class,
            concrete: EloquentFindUserByIdRepository::class
        );
    }

    private function registerInventoryRepositories(): void {}

    public function boot(): void
    {
        //
    }
}
