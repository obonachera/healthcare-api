<?php

declare(strict_types=1);

namespace Lightit\Appointment\App\Resources;

use Dedoc\Scramble\Attributes\SchemaName;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lightit\Appointment\Domain\Models\Appointment;

/**
 * @mixin Appointment
 */
#[SchemaName('Appointment')]
class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->id,
            'doctor' => $this->doctor_id,
            'clinic' => $this->clinic_id,
            'user' => $this->user_id,
            'start' => $this->start_time,
            'end' => $this->end_time,
        ];
    }
}
