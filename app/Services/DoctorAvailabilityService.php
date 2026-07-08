<?php

namespace App\Services;

use App\Models\Doctor;
use App\Models\DoctorAvailability;
use App\Models\AvailabilitySlot;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exceptions\BusinessException;
use Illuminate\Database\Eloquent\Collection;

class DoctorAvailabilityService
{
    public function create(Doctor $doctor, array $data): DoctorAvailability
    {
        DB::beginTransaction();

        try {

            $this->validateOverlappingAvailability($doctor, $data);

            $this->validateSlotDuration($data);

            $this->validateAvailabilityTime($data);

            $availability = $this->storeAvailability($doctor, $data);

            $this->generateAvailabilitySlots($availability);

            DB::commit();

            return $availability;

        } catch (Exception $exception) {

            DB::rollBack();

            throw new BusinessException(
                'Unable to create Doctor availability.',
                500
            );
        }
    }

    private function validateOverlappingAvailability(Doctor $doctor, array $data): void
    {
        $newStartTime = strtotime($data['start_time']);
        $newEndTime = strtotime($data['end_time']);

        $availabilities = DoctorAvailability::where('doctor_id', $doctor->id)
            ->where('available_date', $data['available_date'])
            ->get();

        foreach ($availabilities as $availability) {

            $existingStartTime = strtotime($availability->start_time);
            $existingEndTime = strtotime($availability->end_time);

            if (
                $newStartTime < $existingEndTime &&
                $newEndTime > $existingStartTime
            ) {
                throw new BusinessException(
                    'Doctor already has overlapping availability.',
                    409
                );
            }
        }
    }

    private function validateSlotDuration(array $data): void
    {
        $startTime = strtotime($data['start_time']);
        $endTime = strtotime($data['end_time']);

        $totalMinutes = ($endTime - $startTime) / 60;

        if ($totalMinutes % $data['slot_duration'] != 0) {
            throw new BusinessException(
                'Total duration must be divisible by slot duration.',
                422
            );
        }
    }

    private function validateAvailabilityTime(
        array $data
    ): void
    {
        $endDateTime = Carbon::parse(
            $data['available_date'] . ' ' . $data['end_time']
        );

        if ($endDateTime->isPast()) {

            throw new BusinessException(
                'Availability cannot be created because the selected time has already passed.',
                409
            );
        }
    }

    private function storeAvailability(Doctor $doctor, array $data): DoctorAvailability
    {
        return DoctorAvailability::create([
            'doctor_id' => $doctor->id,
            'available_date' => $data['available_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'slot_duration' => $data['slot_duration'],
        ]);
    }

    private function generateAvailabilitySlots(
        DoctorAvailability $availability
    ): void
    {
        $startTime = Carbon::parse(
            $availability->available_date . ' ' . $availability->start_time
        );

        $endTime = Carbon::parse(
            $availability->available_date . ' ' . $availability->end_time
        );

        while ($startTime < $endTime) {

            $nextTime = $startTime->copy()
                ->addMinutes($availability->slot_duration);

            if ($startTime->isPast()) {
                $startTime = $nextTime;
                continue;
            }

            AvailabilitySlot::create([
                'availability_id' => $availability->id,
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $nextTime->format('H:i:s'),
            ]);

            $startTime = $nextTime;
        }
    }

    public function getAvailableSlots(
        Doctor $doctor,
        array $data
    ) {

        $availabilities = DoctorAvailability::where('doctor_id', $doctor->id)
            ->where('available_date', $data['date'])
            ->get();


        if ($availabilities->isEmpty()) {
            return collect();
        }

        $availabilityIds = $availabilities->pluck('id');

        $slotIds = AvailabilitySlot::whereIn('availability_id', $availabilityIds)
        ->pluck('id');

        $bookedSlots = Appointment::where('status', 'BOOKED')
        ->whereIn('availability_slot_id', $slotIds)
        ->pluck('availability_slot_id');


        $availableSlots = AvailabilitySlot::whereIn('availability_id', $availabilityIds)
        ->whereNotIn('id', $bookedSlots)
        ->orderBy('start_time')
        ->get();

        return $availableSlots;

    }
}