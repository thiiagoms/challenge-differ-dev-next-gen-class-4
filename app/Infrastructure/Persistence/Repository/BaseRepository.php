<?php

namespace App\Infrastructure\Persistence\Repository;

abstract class BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->handle();
    }

    private function handle(): mixed
    {
        return app($this->model);
    }
}
