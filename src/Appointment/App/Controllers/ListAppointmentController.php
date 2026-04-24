<?php

declare(strict_types=1);

namespace Lightit\Appointment\App\Controllers;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Lightit\Appointment\App\Resources\AppointmentResource;
use Lightit\Appointment\Domain\Actions\ListAppointmentAction;

#[Group('Appointments')]
final readonly class ListAppointmentController
{
    #[Endpoint(
        operationId: 'listAppointments',
        title: 'List appointments',
        description: 'Retrieves a list of appointments.'
    )]
    public function __invoke(ListAppointmentAction $action): JsonResponse
    {
        $appointments = $action->execute();

        return AppointmentResource::collection($appointments)
            ->response();
    }
}
