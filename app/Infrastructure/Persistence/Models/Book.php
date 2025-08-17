<?php

namespace App\Infrastructure\Persistence\Models;

use Database\Factories\Infrastructure\Persistence\Models\BookFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<BookFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
    ];
}
