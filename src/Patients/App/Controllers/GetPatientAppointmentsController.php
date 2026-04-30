<?php

declare(strict_types=1);

namespace Lightit\Patients\App\Controllers;

use Dedoc\Scramble\Attributes\Endpoint;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lightit\Appointment\App\Resources\AppointmentResource;
use Lightit\Appointment\Domain\Models\Appointment;
use Lightit\Patients\Domain\Models\Patient;

final readonly class GetPatientAppointmentsController
{
    #[Endpoint(
        operationId: 'getPatientAppointments',
        title: 'Get patient appointments',
        description: 'Retrieves paginated appointments for a patient.'
    )]
    public function __invoke(Patient $patient): AnonymousResourceCollection
    {
        $appointments = Appointment::query()
            ->where('patient_id', $patient->id)
            ->orderBy('start_time')
            ->paginate();

        return AppointmentResource::collection($appointments);
    }
}
