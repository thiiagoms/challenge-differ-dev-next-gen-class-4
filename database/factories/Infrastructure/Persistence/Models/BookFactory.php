<?php

namespace Database\Factories\Infrastructure\Persistence\Models;

use App\Infrastructure\Persistence\Models\Book as LaravelBookModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LaravelBookModel>
 */
class BookFactory extends Factory
{
    protected $model = LaravelBookModel::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->title(),
            'author' => $this->faker->name(),
            'isbn' => $this->faker->isbn13(),
        ];
    }
}
