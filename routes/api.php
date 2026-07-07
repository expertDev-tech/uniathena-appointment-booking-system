<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorAvailabilityController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post(
    '/doctors/{doctor}/availabilities',
    [DoctorAvailabilityController::class, 'store']
);

Route::get(
    '/doctors/{doctor}/available-slots',
    [DoctorAvailabilityController::class, 'availableSlots']
);

