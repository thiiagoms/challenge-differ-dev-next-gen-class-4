<?php

use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\UsersController;
use App\Presentation\Http\Api\V1\Identity\User\All\Controller\RetrieveAllUsersApiController;
use App\Presentation\Http\Api\V1\Reservation\Cost\Controller\ReservationCostApiController;
use App\Presentation\Http\Api\V1\Reservation\Register\Controller\RegisterReservationApiController;
use App\Presentation\Http\Api\V1\Reservation\Return\Controller\ReturnReservationApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Route::get('/users', [UsersController::class, 'getAll']);
Route::get('users', RetrieveAllUsersApiController::class)->name('users');

Route::prefix('reservations')->name('reservations.')->group(function (): void {
    Route::post('', RegisterReservationApiController::class)->name('register');
    Route::post('/return', ReturnReservationApiController::class)->name('return');
    Route::get('cost', ReservationCostApiController::class)->name('cost');
});
