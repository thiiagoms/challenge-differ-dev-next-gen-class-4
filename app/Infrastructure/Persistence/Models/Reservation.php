<?php

namespace App\Infrastructure\Persistence\Models;

use Database\Factories\Infrastructure\Persistence\Models\ReservationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** @use HasFactory<ReservationFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stored_book_id',
        'reserved_at',
        'returned_at',
    ];
}
