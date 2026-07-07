<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BookAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Services\AppointmentService;
use App\Http\Requests\CancelAppointmentRequest;
use App\Models\Appointment;
use App\Http\Requests\RescheduleAppointmentRequest;
use App\Services\NotificationService;
use App\Services\EmailService;

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

    public function cancel(
        CancelAppointmentRequest $request,
        Appointment $appointment
    )
    {
        $appointment = $this->appointmentService->cancel(
            $appointment,
            $request->validated()
        );

        return $this->successResponse(
            new AppointmentResource($appointment),
            'Appointment cancelled successfully.'
        );
    }

    public function reschedule(
        RescheduleAppointmentRequest $request,
        Appointment $appointment
    ) {
        $appointment = $this->appointmentService->reschedule(
            $appointment,
            $request->validated()
        );

        return $this->successResponse(
            new AppointmentResource($appointment),
            'Appointment rescheduled successfully.'
        );
    }
}
