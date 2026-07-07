<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Notification;

class NotificationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function store(
        Appointment $appointment,
        string $type,
        string $message
    ): Notification {

        return Notification::create([
            'appointment_id' => $appointment->id,
            'type' => $type,
            'message' => $message,
        ]);

    }
}
