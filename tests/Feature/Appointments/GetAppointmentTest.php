<?php

declare(strict_types=1);

namespace Tests\Feature\Appointments;

use Database\Factories\AppointmentFactory;
use Database\Factories\PatientFactory;
use Illuminate\Testing\Fluent\AssertableJson;
use Lightit\Appointment\App\Controllers\GetAppointmentController;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

describe('appointments', function (): void {
    /** @see GetAppointmentController */
    it('retrieves an appointment successfully', function (): void {
        $appointment = AppointmentFactory::new()->createOne();

        actingAs(PatientFactory::new()->createOne(), 'api');

        getJson("/api/appointments/{$appointment->id}")
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json): AssertableJson =>
                $json->has(
                    'data',
                    fn (AssertableJson $json): AssertableJson =>
                    $json->where('id', $appointment->id)
                         ->where('doctor', $appointment->doctor_id)
                         ->where('clinic', $appointment->clinic_id)
                         ->where('patient', $appointment->patient_id)
                         ->has('start')
                         ->has('end')
                )
            );
    });

    it('returns 404 when appointment is not found', function (): void {
        actingAs(PatientFactory::new()->createOne(), 'api');

        getJson('/api/appointments/99999')->assertNotFound();
    });
});
