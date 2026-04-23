<?php

declare(strict_types=1);

namespace Lightit\Clinic\App\Controllers;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Lightit\Clinic\App\Resources\ClinicResource;
use Lightit\Clinic\Domain\Models\Clinic;

#[Group('Clinics')]
final readonly class GetClinicController
{
    #[Endpoint(
        operationId: 'getClinic',
        title: 'Get a single clinic',
        description: 'Retrieves a clinic by its ID.'
    )]
    public function __invoke(Clinic $clinic): JsonResponse
    {
        return ClinicResource::make($clinic)
            ->response();
    }
}
