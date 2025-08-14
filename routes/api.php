<?php

use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\UsersController;
use App\Presentation\Http\Api\V1\Reservation\Register\Controller\RegisterReservationApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users', [UsersController::class, 'getAll']);

//Route::post('/reservations', [ReservationsController::class, 'create']);
Route::post('/reservations', RegisterReservationApiController::class);
Route::post('/reservations/return', [ReservationsController::class, 'saveReturn']);
Route::get('/reservations/cost', [ReservationsController::class, 'getCost']);
