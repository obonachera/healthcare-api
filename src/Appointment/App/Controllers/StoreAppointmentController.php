<?php

declare(strict_types=1);

namespace Lightit\Appointment\App\Controllers;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Lightit\Appointment\App\Events\AppointmentCreatedEvent;
use Lightit\Appointment\App\Requests\UpsertAppointmentRequest;
use Lightit\Appointment\App\Resources\AppointmentResource;
use Lightit\Appointment\Domain\Actions\UpsertAppointmentAction;

#[Group('Appointments')]
final readonly class StoreAppointmentController
{
    #[Endpoint(
        operationId: 'storeAppointment',
        title: 'Create an appointment',
        description: 'Creates a new appointment.'
    )]
    public function __invoke(
        UpsertAppointmentRequest $request,
        UpsertAppointmentAction $upsertAppointmentAction,
    ): JsonResponse {
        $appointment = $upsertAppointmentAction->execute($request->toDto());

        AppointmentCreatedEvent::dispatch($appointment);

        return AppointmentResource::make($appointment)
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }
}
