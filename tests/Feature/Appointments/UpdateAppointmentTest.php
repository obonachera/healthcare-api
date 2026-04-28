<?php

declare(strict_types=1);

namespace Tests\Feature\Appointments;

use Carbon\CarbonImmutable;
use Database\Factories\ClinicFactory;
use Database\Factories\DoctorFactory;
use Database\Factories\UserFactory;
use Lightit\Appointment\App\Controllers\UpdateAppointmentController;
use Lightit\Appointment\Domain\Models\Appointment;
use Tests\RequestFactories\UpsertAppointmentRequestFactory;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;

describe('appointments', function (): void {
    /** @see UpdateAppointmentController */
    it('can update an appointment successfully', function (): void {
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

        $newStart = CarbonImmutable::tomorrow()->setHour(11);
        $newEnd = $newStart->addHour();

        $data = [
            'doctor_id'  => $doctor->id,
            'clinic_id'  => $clinic->id,
            'user_id'    => $user->id,
            'start_time' => $newStart->toDateTimeString(),
            'end_time'   => $newEnd->toDateTimeString(),
        ];

        actingAs($user, 'api');

        putJson("/api/appointments/{$appointment->id}", $data)
            ->assertOk();

        assertDatabaseHas('appointments', [
            'id'         => $appointment->id,
            'start_time' => $newStart->toDateTimeString(),
            'end_time'   => $newEnd->toDateTimeString(),
        ]);
    });

    it('allows updating an appointment to its own time slot', function (): void {
        /** @var array{doctor_id: int, user_id: int, clinic_id: int, start_time: string, end_time: string} $data */
        $data = UpsertAppointmentRequestFactory::new()->create();

        $appointment = Appointment::query()->create([
            'doctor_id'  => $data['doctor_id'],
            'user_id'    => $data['user_id'],
            'clinic_id'  => $data['clinic_id'],
            'start_time' => CarbonImmutable::parse($data['start_time']),
            'end_time'   => CarbonImmutable::parse($data['end_time']),
        ]);

        actingAs(UserFactory::new()->createOne(), 'api');

        putJson("/api/appointments/{$appointment->id}", $data)
            ->assertOk();
    });

    it('rejects when end_time is not after start_time on update', function (): void {
        /** @var array{doctor_id: int, user_id: int, clinic_id: int, start_time: string, end_time: string} $data */
        $data = UpsertAppointmentRequestFactory::new()->create();

        $appointment = Appointment::query()->create([
            'doctor_id'  => $data['doctor_id'],
            'user_id'    => $data['user_id'],
            'clinic_id'  => $data['clinic_id'],
            'start_time' => CarbonImmutable::parse($data['start_time']),
            'end_time'   => CarbonImmutable::parse($data['end_time']),
        ]);

        actingAs(UserFactory::new()->createOne(), 'api');

        putJson("/api/appointments/{$appointment->id}", [
            ...$data,
            'start_time' => CarbonImmutable::tomorrow()->setHour(10)->toDateTimeString(),
            'end_time'   => CarbonImmutable::tomorrow()->setHour(9)->toDateTimeString(),
        ])
            ->assertUnprocessable()
            ->assertJson(['error' => ['code' => 'validation_failed']]);
    });

    it('rejects when doctor is not assigned to clinic on update', function (): void {
        /** @var array{doctor_id: int, user_id: int, clinic_id: int, start_time: string, end_time: string} $data */
        $data = UpsertAppointmentRequestFactory::new()->create();

        $appointment = Appointment::query()->create([
            'doctor_id'  => $data['doctor_id'],
            'user_id'    => $data['user_id'],
            'clinic_id'  => $data['clinic_id'],
            'start_time' => CarbonImmutable::parse($data['start_time']),
            'end_time'   => CarbonImmutable::parse($data['end_time']),
        ]);

        $unassignedClinic = ClinicFactory::new()->createOne();

        actingAs(UserFactory::new()->createOne(), 'api');

        putJson("/api/appointments/{$appointment->id}", [
            ...$data,
            'clinic_id' => $unassignedClinic->id,
        ])
            ->assertUnprocessable()
            ->assertJson(['error' => ['code' => 'doctor_not_assigned_to_clinic']]);
    });

    it('rejects when doctor has overlapping appointment from another booking on update', function (): void {
        $doctor = DoctorFactory::new()->createOne();
        $clinic = ClinicFactory::new()->createOne();
        $user = UserFactory::new()->createOne();
        $doctor->clinics()->attach($clinic->id);

        $conflictingStart = CarbonImmutable::tomorrow()->setHour(14);
        $conflictingEnd = $conflictingStart->addHour();

        $appointment = Appointment::query()->create([
            'doctor_id'  => $doctor->id,
            'user_id'    => $user->id,
            'clinic_id'  => $clinic->id,
            'start_time' => CarbonImmutable::tomorrow()->setHour(9),
            'end_time'   => CarbonImmutable::tomorrow()->setHour(10),
        ]);

        $user2 = UserFactory::new()->createOne();
        Appointment::query()->create([
            'doctor_id'  => $doctor->id,
            'user_id'    => $user2->id,
            'clinic_id'  => $clinic->id,
            'start_time' => $conflictingStart,
            'end_time'   => $conflictingEnd,
        ]);

        actingAs($user, 'api');

        putJson("/api/appointments/{$appointment->id}", [
            'doctor_id'  => $doctor->id,
            'clinic_id'  => $clinic->id,
            'user_id'    => $user->id,
            'start_time' => $conflictingStart->toDateTimeString(),
            'end_time'   => $conflictingEnd->toDateTimeString(),
        ])
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
