<?php

namespace App\Services;

use App\Models\AvailabilitySlot;
use App\Models\Appointment;
use App\Exceptions\BusinessException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AppointmentService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    private function generateReferenceNumber(): string
    {
        do {

            $referenceNumber = 'APT-' . strtoupper(Str::random(10));

        } while (
            Appointment::where(
                'reference_number',
                $referenceNumber
            )->exists()
        );

        return $referenceNumber;
    }

    public function create(array $data): Appointment
    {
        DB::beginTransaction();

        try {

            $slot = AvailabilitySlot::where(
                    'id',
                    $data['availability_slot_id']
                )
                ->lockForUpdate()
                ->first();

            if (!$slot) {
                throw new BusinessException(
                    'Availability slot not found.',
                    404
                );
            }

            $appointment = Appointment::where(
                    'availability_slot_id',
                    $slot->id
                )
                ->where('status', 'BOOKED')
                ->first();

            if ($appointment) {
                throw new BusinessException(
                    'This slot is already booked.',
                    409
                );
            }

            $appointment = new Appointment();
            $appointment->patient_id = $data['patient_id'];
            $appointment->availability_slot_id = $slot->id;
            $appointment->reference_number = $this->generateReferenceNumber();
            $appointment->status = 'BOOKED';

            $appointment->save();

            DB::commit();

            return $appointment;

        } catch (Exception $exception) {

            DB::rollBack();

            throw new BusinessException(
                'Unable to book appointment.',
                500
            );
        }
    }
}
