<?php

declare(strict_types=1);

namespace Lightit\Doctor\App\Controllers;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\JsonResponse;
use Lightit\Doctor\App\Resources\DoctorResource;
use Lightit\Doctor\Domain\Models\Doctor;

#[Group('Doctors')]
final readonly class GetDoctorController
{
    #[Endpoint(
        operationId: 'getDoctor',
        title: 'Get a single doctor',
        description: 'Retrieves a doctor by its ID.'
    )]
    public function __invoke(Doctor $doctor): JsonResponse
    {
        return DoctorResource::make(
            $doctor->load(['clinics' => fn (BelongsToMany $q) => $q->withCount('doctors')])
        )
            ->response();
    }
}
