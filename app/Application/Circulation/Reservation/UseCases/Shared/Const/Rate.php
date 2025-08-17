<?php

namespace App\Application\Circulation\Reservation\UseCases\Shared\Const;

abstract class Rate
{
    private function __construct() {}

    public const float DAILY_RATE_TAX = 4.50;
}
