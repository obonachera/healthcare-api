<?php

declare(strict_types=1);

namespace Lightit\Doctor\App\Controllers;

use Dedoc\Scramble\Attributes\Endpoint;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Lightit\Appointment\Domain\Models\Appointment;
use Lightit\Doctor\App\Requests\DoctorAvailabilityRequest;
use Lightit\Doctor\App\Resources\DoctorAvailabilityResource;
use Lightit\Doctor\Domain\Models\Doctor;

final readonly class GetDoctorAvailabilityController
{
    #[Endpoint(
        operationId: 'getDoctorAvailability',
        title: 'Get doctor booked slots',
        description: 'Returns booked appointment slots for a doctor on the given date.'
    )]
    public function __invoke(Doctor $doctor, DoctorAvailabilityRequest $request): AnonymousResourceCollection
    {
        $date = $request->date('date');

        $booked = Appointment::query()
            ->where('doctor_id', $doctor->id)
            ->whereDate('start_time', $date)
            ->orderBy('start_time')
            ->get();

        return DoctorAvailabilityResource::collection($booked);
    }
}
