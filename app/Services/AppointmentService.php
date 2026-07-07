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

    public function cancel(
        Appointment $appointment,
        array $data
    ): Appointment
    {
        DB::beginTransaction();

        try {

            $this->validateAppointmentStatus($appointment);

            $appointment->status = 'CANCELLED';
            $appointment->cancel_reason = $data['cancel_reason'];

            $appointment->save();

            DB::commit();

            return $appointment;

        }catch (Exception $exception) {

            DB::rollBack();

            throw new BusinessException(
                'Unable to cancel appointment.',
                500
            );
        }
    }

    public function reschedule(
        Appointment $appointment,
        array $data
    ): Appointment
    {
        DB::beginTransaction();

        try {

            $this->validateAppointmentStatus($appointment);

            $this->validateDoctor(
                $appointment,
                $data['availability_slot_id']
            );

            $this->checkSlotAlreadyBooked(
                $data['availability_slot_id']
            );

            $appointment->availability_slot_id =
                $data['availability_slot_id'];

            $appointment->save();

            DB::commit();

            return $appointment;

        } catch (Exception $exception) {

            DB::rollBack();

            throw new BusinessException(
                'Unable to reshedule appointment.',
                500
            );
        }
    }

    private function validateAppointmentStatus(
        Appointment $appointment
    ): void
    {
        if ($appointment->status != 'BOOKED') {

            throw new BusinessException(
                'Only booked appointments can perform this action.',
                409
            );
        }
    }

    private function validateDoctor(
        Appointment $appointment,
        int $slotId
    ): void
    {
        $currentDoctorId = $appointment->slot
            ->availability
            ->doctor_id;

        $newSlot = AvailabilitySlot::find($slotId);

        $newDoctorId = $newSlot->availability
            ->doctor_id;

        if ($currentDoctorId != $newDoctorId) {

            throw new BusinessException(
                'You can only reschedule with the same doctor.',
                409
            );
        }
    }

    private function checkSlotAlreadyBooked(
        int $slotId
    ): void
    {
        $appointment = Appointment::where(
                'availability_slot_id',
                $slotId
            )
            ->where('status', 'BOOKED')
            ->first();

        if ($appointment) {

            throw new BusinessException(
                'This slot is already booked.',
                409
            );
        }
    }

}
