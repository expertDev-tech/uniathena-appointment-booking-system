<?php

namespace App\Listeners;

use App\Events\AppointmentNotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\NotificationService;
use App\Services\EmailService;

class SendAppointmentNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private NotificationService $notificationService,
        private EmailService $emailService
    )
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AppointmentNotificationEvent $event): void
    {
        $notification = $this->notificationService->store(
            $event->appointment,
            $event->type,
            $event->message
        );

        $this->emailService->send($notification);
    }
}
