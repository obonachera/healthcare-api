<?php

declare(strict_types=1);

namespace Lightit\Appointment\App\Requests;

use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Lightit\Appointment\Domain\DataTransferObjects\AppointmentDto;

class UpsertAppointmentRequest extends FormRequest
{
    public const string DOCTOR_ID = 'doctor_id';

    public const string PATIENT_ID = 'patient_id';

    public const string CLINIC_ID = 'clinic_id';

    public const string START_TIME = 'start_time';

    public const string END_TIME = 'end_time';

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            self::DOCTOR_ID => Rule::exists('doctors', 'id'),
            self::CLINIC_ID => Rule::exists('clinics', 'id'),
            self::PATIENT_ID => Rule::exists('patients', 'id'),
            self::START_TIME => ['required', 'date', 'after:' . now()],
            self::END_TIME => ['required', 'date', 'after:' . self::START_TIME],
        ];
    }

    public function toDto(): AppointmentDto
    {
        return new AppointmentDto(
            doctorId: $this->integer(self::DOCTOR_ID),
            patientId: $this->integer(self::PATIENT_ID),
            clinicId: $this->integer(self::CLINIC_ID),
            startTime: CarbonImmutable::parse($this->string(self::START_TIME)->toString()),
            endTime: CarbonImmutable::parse($this->string(self::END_TIME)->toString()),
        );
    }
}
