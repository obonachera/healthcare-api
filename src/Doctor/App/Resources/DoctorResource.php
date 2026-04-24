<?php

declare(strict_types=1);

namespace Lightit\Doctor\App\Resources;

use Dedoc\Scramble\Attributes\SchemaName;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lightit\Clinic\App\Resources\ClinicResource;
use Lightit\Doctor\Domain\Models\Doctor;

/**
 * @mixin Doctor
 */
#[SchemaName('Doctor')]
class DoctorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'clinics' => ClinicResource::collection($this->whenLoaded('clinics')),
        ];
    }
}
