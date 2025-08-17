<?php

namespace App\Infrastructure\Persistence\Models;

use Database\Factories\Infrastructure\Persistence\Models\StoredBookFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoredBook extends Model
{
    /** @use HasFactory<StoredBookFactory> */
    use HasFactory;
}
