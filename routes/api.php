<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoctorAvailabilityController;
use App\Http\Controllers\AppointmentController;

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

Route::post(
    '/appointments',
    [AppointmentController::class, 'store']
);

Route::patch(
    '/appointments/{appointment}/cancel',
    [AppointmentController::class, 'cancel']
);

Route::patch(
    '/appointments/{appointment}/reschedule',
    [AppointmentController::class, 'reschedule']
);

