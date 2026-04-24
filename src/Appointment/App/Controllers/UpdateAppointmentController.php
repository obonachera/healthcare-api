<?php

declare(strict_types=1);

namespace Lightit\Appointment\App\Controllers;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Lightit\Appointment\App\Requests\UpsertAppointmentRequest;
use Lightit\Appointment\App\Resources\AppointmentResource;
use Lightit\Appointment\Domain\Actions\UpsertAppointmentAction;
use Lightit\Appointment\Domain\Models\Appointment;

#[Group('Appointments')]
final readonly class UpdateAppointmentController
{
    #[Endpoint(
        operationId: 'updateAppointment',
        title: 'Update an appointment',
        description: 'Updates an existing appointment by its ID.'
    )]
    public function __invoke(
        Appointment $appointment,
        UpsertAppointmentRequest $request,
        UpsertAppointmentAction $upsertAppointmentAction,
    ): JsonResponse {
        $appointment = $upsertAppointmentAction->execute($request->toDto(), $appointment);

        return AppointmentResource::make($appointment)
            ->response();
    }
}
