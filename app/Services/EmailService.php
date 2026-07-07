<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\Notification;
use Exception;

class EmailService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function send(Notification $notification): void
    {
        try {

            Log::info(
                'Email sent successfully.',
                [
                    'appointment_id' => $notification->appointment_id,
                    'type' => $notification->type,
                    'message' => $notification->message,
                ]
            );

            $notification->status = 'SENT';
            $notification->sent_at = now();

        } catch (Exception $exception) {

            $notification->status = 'FAILED';
            $notification->retry_count++;

            $notification->last_error = $exception->getMessage();

        }

        $notification->save();
    }
}
