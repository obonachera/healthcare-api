<?php

declare(strict_types=1);

namespace Lightit\Appointment\Domain\Actions;

use Lightit\Appointment\Domain\Models\Appointment;

class GetAppointmentAction
{
    public function execute(Appointment $appointment): Appointment
    {
        return Appointment::query()->findOrFail($appointment->id);
    }
}
