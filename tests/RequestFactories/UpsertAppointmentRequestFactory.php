<?php

declare(strict_types=1);

namespace Tests\RequestFactories;

use Carbon\CarbonImmutable;
use Database\Factories\ClinicFactory;
use Database\Factories\DoctorFactory;
use Database\Factories\UserFactory;
use Worksome\RequestFactories\RequestFactory;

class UpsertAppointmentRequestFactory extends RequestFactory
{
    public function definition(): array
    {
        $doctor = DoctorFactory::new()->createOne();
        $clinic = ClinicFactory::new()->createOne();
        $user   = UserFactory::new()->createOne();
        $doctor->clinics()->attach($clinic->id);

        $start = CarbonImmutable::tomorrow()->setHour(9);

        return [
            'doctor_id'  => $doctor->id,
            'clinic_id'  => $clinic->id,
            'user_id'    => $user->id,
            'start_time' => $start->toDateTimeString(),
            'end_time'   => $start->addHour()->toDateTimeString(),
        ];
    }
}
