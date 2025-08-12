<?php

namespace Database\Seeders;

use App\Models\StoredBook;
use Illuminate\Database\Seeder;

class CreateStoredBooks extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StoredBook::factory()->count(100)->create();
    }
}
