<?php

declare(strict_types=1);

namespace Lightit\Doctor\App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lightit\Appointment\Domain\Models\Appointment;

/** @mixin Appointment */
class DoctorAvailabilityResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        /** @var Appointment $appointment */
        $appointment = $this->resource;

        return [
            'start' => $appointment->start_time,
            'end'   => $appointment->end_time,
        ];
    }
}
