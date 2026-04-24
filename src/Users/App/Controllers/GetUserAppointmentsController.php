<?php

declare(strict_types=1);

namespace Lightit\Users\App\Controllers;

use Dedoc\Scramble\Attributes\Endpoint;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lightit\Appointment\App\Resources\AppointmentResource;
use Lightit\Appointment\Domain\Models\Appointment;
use Lightit\Users\Domain\Models\User;

final readonly class GetUserAppointmentsController
{
    #[Endpoint(
        operationId: 'getUserAppointments',
        title: 'Get user appointments',
        description: 'Retrieves paginated appointments for a user.'
    )]
    public function __invoke(User $user): AnonymousResourceCollection
    {
        $appointments = Appointment::query()
            ->where('user_id', $user->id)
            ->orderBy('start_time')
            ->paginate();

        return AppointmentResource::collection($appointments);
    }
}
