<?php

namespace App\Infrastructure\Persistence\Repository\Circulation\Reservation;

use App\Infrastructure\Persistence\Models\Reservation as LaravelReservationModel;
use App\Infrastructure\Persistence\Repository\BaseRepository;

abstract class BaseReservationRepository extends BaseRepository
{
    protected $model = LaravelReservationModel::class;
}
