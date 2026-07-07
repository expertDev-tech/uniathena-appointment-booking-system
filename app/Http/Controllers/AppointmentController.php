<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Services\AppointmentService;

class AppointmentController extends BaseController
{
    protected AppointmentService $appointmentService;

    public function __construct(AppointmentService $appointmentService){
        $this->appointmentService = $appointmentService;
    }

    public function store(BookAppointmentRequest $request)
    {
        $appointment = $this->appointmentService->create(
            $request->validated()
        );

        return $this->successResponse(
            new AppointmentResource($appointment),
            'Appointment booked successfully.'
        );
    }
}
