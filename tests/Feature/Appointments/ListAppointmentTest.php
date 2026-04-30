<?php

declare(strict_types=1);

namespace Tests\Feature\Appointments;

use Database\Factories\AppointmentFactory;
use Database\Factories\ClinicFactory;
use Database\Factories\DoctorFactory;
use Database\Factories\PatientFactory;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

describe('appointments', function (): void {
    /** @see ListAppointmentController */
    it('lists all appointments', function (): void {
        $doctor = DoctorFactory::new()->createOne();
        $clinic = ClinicFactory::new()->createOne();

        AppointmentFactory::new()->forDoctor($doctor)->forClinic($clinic)->withTimeSlot(9, 10)->createOne();
        AppointmentFactory::new()->forDoctor($doctor)->forClinic($clinic)->withTimeSlot(11, 12)->createOne();

        actingAs(PatientFactory::new()->createOne(), 'api');

        getJson('/api/appointments')
            ->assertOk()
            ->assertJsonCount(2, 'data');
    });

    it('can filter appointments by doctor_id', function (): void {
        $clinic = ClinicFactory::new()->createOne();
        $doctor1 = DoctorFactory::new()->createOne();
        $doctor2 = DoctorFactory::new()->createOne();

        AppointmentFactory::new()->forDoctor($doctor1)->forClinic($clinic)->withTimeSlot(9, 10)->createOne();
        AppointmentFactory::new()->forDoctor($doctor2)->forClinic($clinic)->withTimeSlot(11, 12)->createOne();

        actingAs(PatientFactory::new()->createOne(), 'api');

        getJson("/api/appointments?filter[doctor_id]={$doctor1->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data');
    });

    it('can filter appointments by clinic_id', function (): void {
        $doctor = DoctorFactory::new()->createOne();
        $clinic1 = ClinicFactory::new()->createOne();
        $clinic2 = ClinicFactory::new()->createOne();

        AppointmentFactory::new()->forDoctor($doctor)->forClinic($clinic1)->withTimeSlot(9, 10)->createOne();
        AppointmentFactory::new()->forDoctor($doctor)->forClinic($clinic2)->withTimeSlot(11, 12)->createOne();

        actingAs(PatientFactory::new()->createOne(), 'api');

        getJson("/api/appointments?filter[clinic_id]={$clinic1->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data');
    });
});
