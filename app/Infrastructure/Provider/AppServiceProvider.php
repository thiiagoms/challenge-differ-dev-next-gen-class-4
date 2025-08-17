<?php

namespace App\Infrastructure\Provider;

use App\Domain\Circulation\Reservation\Factory\DefaultReservationFactory;
use App\Domain\Circulation\Reservation\Factory\ReservationFactoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            abstract: ReservationFactoryInterface::class,
            concrete: DefaultReservationFactory::class
        );
    }

    public function boot(): void
    {
        //
    }
}
