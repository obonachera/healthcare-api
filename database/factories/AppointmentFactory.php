<?php

declare(strict_types=1);

namespace Database\Factories;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Lightit\Appointment\Domain\Models\Appointment;
use Lightit\Clinic\Domain\Models\Clinic;
use Lightit\Doctor\Domain\Models\Doctor;
use Lightit\Users\Domain\Models\User;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $doctor = DoctorFactory::new()->createOne();
        $clinic = ClinicFactory::new()->createOne();
        $user   = UserFactory::new()->createOne();

        return [
            'doctor_id'  => $doctor->id,
            'user_id'    => $user->id,
            'clinic_id'  => $clinic->id,
            'start_time' => CarbonImmutable::tomorrow()->setHour(9),
            'end_time'   => CarbonImmutable::tomorrow()->setHour(10),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Appointment $appointment): void {
            /** @var Doctor $doctor */
            $doctor = Doctor::query()->findOrFail($appointment->doctor_id);

            if (! $doctor->clinics()->where('clinics.id', $appointment->clinic_id)->exists()) {
                $doctor->clinics()->attach($appointment->clinic_id);
            }
        });
    }

    public function forDoctor(Doctor $doctor): static
    {
        return $this->state(['doctor_id' => $doctor->id]);
    }

    public function forUser(User $user): static
    {
        return $this->state(['user_id' => $user->id]);
    }

    public function forClinic(Clinic $clinic): static
    {
        return $this->state(['clinic_id' => $clinic->id]);
    }

    public function withTimeSlot(int $startHour, int $endHour): static
    {
        return $this->state([
            'start_time' => CarbonImmutable::tomorrow()->setHour($startHour),
            'end_time'   => CarbonImmutable::tomorrow()->setHour($endHour),
        ]);
    }
}
