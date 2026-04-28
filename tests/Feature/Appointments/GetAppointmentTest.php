<?php

declare(strict_types=1);

namespace Tests\Feature\Appointments;

use Carbon\CarbonImmutable;
use Database\Factories\ClinicFactory;
use Database\Factories\DoctorFactory;
use Database\Factories\UserFactory;
use Illuminate\Testing\Fluent\AssertableJson;
use Lightit\Appointment\App\Controllers\GetAppointmentController;
use Lightit\Appointment\Domain\Models\Appointment;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

describe('appointments', function (): void {
    /** @see GetAppointmentController */
    it('retrieves an appointment successfully', function (): void {
        $doctor = DoctorFactory::new()->createOne();
        $clinic = ClinicFactory::new()->createOne();
        $user = UserFactory::new()->createOne();
        $doctor->clinics()->attach($clinic->id);

        $appointment = Appointment::query()->create([
            'doctor_id'  => $doctor->id,
            'user_id'    => $user->id,
            'clinic_id'  => $clinic->id,
            'start_time' => CarbonImmutable::tomorrow()->setHour(9),
            'end_time'   => CarbonImmutable::tomorrow()->setHour(10),
        ]);

        actingAs($user, 'api');

        getJson("/api/appointments/{$appointment->id}")
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json): AssertableJson =>
                $json->has(
                    'data',
                    fn (AssertableJson $json): AssertableJson =>
                    $json->where('id', $appointment->id)
                         ->where('doctor', $doctor->id)
                         ->where('clinic', $clinic->id)
                         ->where('user', $user->id)
                         ->has('start')
                         ->has('end')
                )
            );
    });

    it('returns 404 when appointment is not found', function (): void {
        actingAs(UserFactory::new()->createOne(), 'api');

        getJson('/api/appointments/99999')->assertNotFound();
    });
});
