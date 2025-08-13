<?php

namespace App\Domain\Circulation\Reservation\Status\Factory;

use App\Domain\Circulation\Reservation\Status\Implementation\Pending;
use App\Domain\Circulation\Reservation\Status\Implementation\Returned;
use App\Domain\Circulation\Reservation\Status\Interface\StatusInterface;
use App\Domain\Circulation\Reservation\Status\Status;

abstract class StatusFactory
{
    public static function build(Status $status): StatusInterface
    {
        return match ($status) {
            Status::PENDING => new Pending,
            Status::RETURNED => new Returned,
        };
    }
}
