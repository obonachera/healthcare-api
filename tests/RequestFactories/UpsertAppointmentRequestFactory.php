<?php

declare(strict_types=1);

namespace Tests\RequestFactories;

use Database\Factories\AppointmentFactory;
use Lightit\Appointment\Domain\Models\Appointment;
use Worksome\RequestFactories\RequestFactory;

class UpsertAppointmentRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        $appointment = AppointmentFactory::new()->createOne();

        return [
            'doctor_id'  => $appointment->doctor_id,
            'clinic_id'  => $appointment->clinic_id,
            'user_id'    => $appointment->user_id,
            'start_time' => $appointment->start_time->toDateTimeString(),
            'end_time'   => $appointment->end_time->toDateTimeString(),
        ];
    }

    public function forAppointment(Appointment $appointment): static
    {
        return $this->state([
            'doctor_id'  => $appointment->doctor_id,
            'clinic_id'  => $appointment->clinic_id,
            'user_id'    => $appointment->user_id,
            'start_time' => $appointment->start_time->toDateTimeString(),
            'end_time'   => $appointment->end_time->toDateTimeString(),
        ]);
    }
}
