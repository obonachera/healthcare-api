<?php

declare(strict_types=1);

namespace Lightit\Appointment\App\Controllers;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Lightit\Appointment\App\Resources\AppointmentResource;
use Lightit\Appointment\Domain\Models\Appointment;

#[Group('Appointments')]
final readonly class GetAppointmentController
{
    #[Endpoint(
        operationId: 'getAppointment',
        title: 'Get an appointment',
        description: 'Retrieves an appointment by its ID.'
    )]
    public function __invoke(Appointment $appointment): JsonResponse
    {
        return AppointmentResource::make($appointment)
            ->response();
    }
}
