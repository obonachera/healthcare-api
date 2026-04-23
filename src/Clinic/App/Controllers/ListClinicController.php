<?php

declare(strict_types=1);

namespace Lightit\Clinic\App\Controllers;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Lightit\Clinic\App\Resources\ClinicResource;
use Lightit\Clinic\Domain\Actions\ListClinicAction;

#[Group('Clinics')]
final readonly class ListClinicController
{
    #[Endpoint(
        operationId: 'listClinics',
        title: 'List clinics',
        description: 'Retrieves a list of clinics.'
    )]
    public function __invoke(
        ListClinicAction $listClinicAction,
    ): JsonResponse {
        $clinic = $listClinicAction->execute();

        return ClinicResource::collection($clinic)
            ->response();
    }
}
