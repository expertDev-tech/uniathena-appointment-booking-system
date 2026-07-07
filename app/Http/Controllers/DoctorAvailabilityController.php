<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorAvailabilityRequest;
use App\Http\Resources\AvailabilityResource;
use App\Models\Doctor;
use App\Services\DoctorAvailabilityService;

class DoctorAvailabilityController extends BaseController
{
    protected DoctorAvailabilityService $doctorAvailabilityService;

    public function __construct(DoctorAvailabilityService $doctorAvailabilityService)
    {
        $this->doctorAvailabilityService = $doctorAvailabilityService;
    }

    public function store(StoreDoctorAvailabilityRequest $request, Doctor $doctor)
    {
        $availability = $this->doctorAvailabilityService->create(
            $doctor,
            $request->validated()
        );

        return $this->successResponse(
            new AvailabilityResource($availability),
            'Availability created successfully.',
            201
        );
    }
}