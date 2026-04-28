<?php

declare(strict_types=1);

namespace Lightit\Appointment\Domain\Actions;

use Lightit\Appointment\Domain\DataTransferObjects\AppointmentDto;
use Lightit\Appointment\Domain\Exceptions\DoctorNotAssignedToClinicException;
use Lightit\Appointment\Domain\Exceptions\DoctorNotAvailableException;
use Lightit\Appointment\Domain\Exceptions\InvalidTimeRangeException;
use Lightit\Appointment\Domain\Exceptions\UserNotAvailableException;
use Lightit\Appointment\Domain\Models\Appointment;
use Lightit\Doctor\Domain\Models\Doctor;

class UpsertAppointmentAction
{
    public function __construct(private readonly CheckAvailabilityAction $checkAvailability)
    {
    }

    public function execute(AppointmentDto $dto, Appointment|null $appointment = null): Appointment
    {
        
        $doctorBelongsToClinic = Doctor::query()
            ->where('id', $dto->doctorId)
            ->whereHas(
                'clinics',
                fn (\Illuminate\Contracts\Database\Query\Builder $q) => $q->where('id', $dto->clinicId)
            )
            ->exists();

        if (! $doctorBelongsToClinic) {
            throw new DoctorNotAssignedToClinicException();
        }

        $excludeId = $appointment?->id;

        if (! $this->checkAvailability->execute('user_id', $dto->userId, $dto->startTime, $dto->endTime, $excludeId)) {
            throw new UserNotAvailableException();
        }

        if (! $this->checkAvailability->execute(
            'doctor_id',
            $dto->doctorId,
            $dto->startTime,
            $dto->endTime,
            $excludeId
        )) {
            throw new DoctorNotAvailableException();
        }

        $appointment ??= new Appointment();

        $appointment->doctor_id = $dto->doctorId;
        $appointment->user_id = $dto->userId;
        $appointment->clinic_id = $dto->clinicId;
        $appointment->start_time = $dto->startTime;
        $appointment->end_time = $dto->endTime;
         
        $appointment->saveOrFail();

        return $appointment->load(['doctor', 'user', 'clinic']);
    }
}
