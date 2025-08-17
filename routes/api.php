<?php

use App\Presentation\Http\Api\V1\Circulation\Reservation\Cost\Controller\ReservationCostApiController;
use App\Presentation\Http\Api\V1\Circulation\Reservation\Register\Controller\RegisterReservationApiController;
use App\Presentation\Http\Api\V1\Circulation\Reservation\Return\Controller\ReturnReservationApiController;
use App\Presentation\Http\Api\V1\Identity\User\All\Controller\RetrieveAllUsersApiController;
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
