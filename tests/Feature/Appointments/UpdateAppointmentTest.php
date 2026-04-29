<?php

declare(strict_types=1);

namespace Tests\Feature\Appointments;

use Carbon\CarbonImmutable;
use Database\Factories\AppointmentFactory;
use Database\Factories\ClinicFactory;
use Database\Factories\DoctorFactory;
use Database\Factories\UserFactory;
use Lightit\Appointment\App\Controllers\UpdateAppointmentController;
use Tests\RequestFactories\UpsertAppointmentRequestFactory;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;

describe('appointments', function (): void {
    /** @see UpdateAppointmentController */
    it('can update an appointment successfully', function (): void {
        $appointment = AppointmentFactory::new()->createOne();

        $newStart = CarbonImmutable::tomorrow()->setHour(11);

        $data = UpsertAppointmentRequestFactory::new()->forAppointment($appointment)->create([
            'start_time' => $newStart->toDateTimeString(),
            'end_time'   => $newStart->addHour()->toDateTimeString(),
        ]);

        actingAs(UserFactory::new()->createOne(), 'api');

        putJson("/api/appointments/{$appointment->id}", $data)
            ->assertOk();

        assertDatabaseHas('appointments', [
            'id'         => $appointment->id,
            'start_time' => $newStart->toDateTimeString(),
            'end_time'   => $newStart->addHour()->toDateTimeString(),
        ]);
    });

    it('allows updating an appointment to its own time slot', function (): void {
        $appointment = AppointmentFactory::new()->createOne();

        $data = UpsertAppointmentRequestFactory::new()->forAppointment($appointment)->create();

        actingAs(UserFactory::new()->createOne(), 'api');

        putJson("/api/appointments/{$appointment->id}", $data)
            ->assertOk();
    });

    it('rejects when end_time is not after start_time on update', function (): void {
        $appointment = AppointmentFactory::new()->createOne();

        $data = UpsertAppointmentRequestFactory::new()->forAppointment($appointment)->create([
            'start_time' => CarbonImmutable::tomorrow()->setHour(10)->toDateTimeString(),
            'end_time'   => CarbonImmutable::tomorrow()->setHour(9)->toDateTimeString(),
        ]);

        actingAs(UserFactory::new()->createOne(), 'api');

        putJson("/api/appointments/{$appointment->id}", $data)
            ->assertUnprocessable()
            ->assertJson(['error' => ['code' => 'validation_failed']]);
    });

    it('rejects when doctor is not assigned to clinic on update', function (): void {
        $appointment      = AppointmentFactory::new()->createOne();
        $unassignedClinic = ClinicFactory::new()->createOne();

        $data = UpsertAppointmentRequestFactory::new()->forAppointment($appointment)->create([
            'clinic_id' => $unassignedClinic->id,
        ]);

        actingAs(UserFactory::new()->createOne(), 'api');

        putJson("/api/appointments/{$appointment->id}", $data)
            ->assertUnprocessable()
            ->assertJson(['error' => ['code' => 'doctor_not_assigned_to_clinic']]);
    });

    it('rejects when doctor has overlapping appointment from another booking on update', function (): void {
        $doctor = DoctorFactory::new()->createOne();
        $clinic = ClinicFactory::new()->createOne();

        $appointment = AppointmentFactory::new()
            ->forDoctor($doctor)
            ->forClinic($clinic)
            ->withTimeSlot(9, 10)
            ->createOne();

        AppointmentFactory::new()
            ->forDoctor($doctor)
            ->forClinic($clinic)
            ->withTimeSlot(14, 15)
            ->createOne();

        $conflictingStart = CarbonImmutable::tomorrow()->setHour(14);

        $data = UpsertAppointmentRequestFactory::new()->forAppointment($appointment)->create([
            'start_time' => $conflictingStart->toDateTimeString(),
            'end_time'   => $conflictingStart->addHour()->toDateTimeString(),
        ]);

        actingAs(UserFactory::new()->createOne(), 'api');

        putJson("/api/appointments/{$appointment->id}", $data)
            ->assertUnprocessable()
            ->assertJson(['error' => ['code' => 'doctor_not_available']]);
    });

    it('returns 404 when appointment is not found', function (): void {
        /** @var array{doctor_id: int, user_id: int, clinic_id: int, start_time: string, end_time: string} $data */
        $data = UpsertAppointmentRequestFactory::new()->create();

        actingAs(UserFactory::new()->createOne(), 'api');

        putJson('/api/appointments/99999', $data)->assertNotFound();
    });
});
