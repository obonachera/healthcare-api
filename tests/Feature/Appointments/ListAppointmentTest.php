<?php

declare(strict_types=1);

namespace Tests\Feature\Appointments;

use Carbon\CarbonImmutable;
use Database\Factories\ClinicFactory;
use Database\Factories\DoctorFactory;
use Database\Factories\UserFactory;
use Lightit\Appointment\App\Controllers\ListAppointmentController;
use Lightit\Appointment\Domain\Models\Appointment;
use function Pest\Laravel\getJson;

describe('appointments', function (): void {
    /** @see ListAppointmentController */
    it('lists all appointments', function (): void {
        $doctor = DoctorFactory::new()->createOne();
        $clinic = ClinicFactory::new()->createOne();
        $user = UserFactory::new()->createOne();
        $doctor->clinics()->attach($clinic->id);

        Appointment::query()->create([
            'doctor_id'  => $doctor->id,
            'user_id'    => $user->id,
            'clinic_id'  => $clinic->id,
            'start_time' => CarbonImmutable::tomorrow()->setHour(9),
            'end_time'   => CarbonImmutable::tomorrow()->setHour(10),
        ]);

        Appointment::query()->create([
            'doctor_id'  => $doctor->id,
            'user_id'    => $user->id,
            'clinic_id'  => $clinic->id,
            'start_time' => CarbonImmutable::tomorrow()->setHour(11),
            'end_time'   => CarbonImmutable::tomorrow()->setHour(12),
        ]);

        getJson('/api/appointments')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    });

    it('can filter appointments by doctor_id', function (): void {
        $doctor1 = DoctorFactory::new()->createOne();
        $doctor2 = DoctorFactory::new()->createOne();
        $clinic = ClinicFactory::new()->createOne();
        $user = UserFactory::new()->createOne();
        $doctor1->clinics()->attach($clinic->id);
        $doctor2->clinics()->attach($clinic->id);

        Appointment::query()->create([
            'doctor_id'  => $doctor1->id,
            'user_id'    => $user->id,
            'clinic_id'  => $clinic->id,
            'start_time' => CarbonImmutable::tomorrow()->setHour(9),
            'end_time'   => CarbonImmutable::tomorrow()->setHour(10),
        ]);

        Appointment::query()->create([
            'doctor_id'  => $doctor2->id,
            'user_id'    => $user->id,
            'clinic_id'  => $clinic->id,
            'start_time' => CarbonImmutable::tomorrow()->setHour(11),
            'end_time'   => CarbonImmutable::tomorrow()->setHour(12),
        ]);

        getJson("/api/appointments?filter[doctor_id]={$doctor1->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data');
    });

    it('can filter appointments by clinic_id', function (): void {
        $doctor = DoctorFactory::new()->createOne();
        $clinic1 = ClinicFactory::new()->createOne();
        $clinic2 = ClinicFactory::new()->createOne();
        $user = UserFactory::new()->createOne();
        $doctor->clinics()->attach($clinic1->id);
        $doctor->clinics()->attach($clinic2->id);

        Appointment::query()->create([
            'doctor_id'  => $doctor->id,
            'user_id'    => $user->id,
            'clinic_id'  => $clinic1->id,
            'start_time' => CarbonImmutable::tomorrow()->setHour(9),
            'end_time'   => CarbonImmutable::tomorrow()->setHour(10),
        ]);

        Appointment::query()->create([
            'doctor_id'  => $doctor->id,
            'user_id'    => $user->id,
            'clinic_id'  => $clinic2->id,
            'start_time' => CarbonImmutable::tomorrow()->setHour(11),
            'end_time'   => CarbonImmutable::tomorrow()->setHour(12),
        ]);

        getJson("/api/appointments?filter[clinic_id]={$clinic1->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data');
    });
});
