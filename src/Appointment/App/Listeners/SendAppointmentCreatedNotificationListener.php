<?php

declare(strict_types=1);

namespace Lightit\Appointment\App\Listeners;

use Lightit\Appointment\App\Events\AppointmentCreatedEvent;
use Lightit\Appointment\App\Notifications\AppointmentCreatedNotification;

class SendAppointmentCreatedNotificationListener
{
    public function handle(AppointmentCreatedEvent $event): void
    {
        $event->appointment->user->notify(new AppointmentCreatedNotification($event->appointment));
    }
}
