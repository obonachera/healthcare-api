<?php

declare(strict_types=1);

namespace Lightit\Appointment\App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Lightit\Appointment\Domain\Models\Appointment;

class AppointmentCreatedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly Appointment $appointment,
    ) {
    }
}
