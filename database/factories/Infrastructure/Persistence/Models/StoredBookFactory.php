<?php

namespace Database\Factories\Infrastructure\Persistence\Models;

use App\Infrastructure\Persistence\Models\Book as LaravelBookModel;
use App\Infrastructure\Persistence\Models\StoredBook as LaravelStoredBookModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LaravelStoredBookModel>
 */
class StoredBookFactory extends Factory
{
    protected $model = LaravelStoredBookModel::class;

    public function definition(): array
    {
        return [
            'book_id' => LaravelBookModel::factory()->createOne(),
        ];
    }
}
