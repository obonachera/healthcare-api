<?php

declare(strict_types=1);

namespace Lightit\Clinic\App\Controllers;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Lightit\Clinic\App\Requests\UpsertClinicRequest;
use Lightit\Clinic\App\Resources\ClinicResource;
use Lightit\Clinic\Domain\Actions\StoreClinicAction;

#[Group('Clinics')]
final readonly class StoreClinicController
{
    #[Endpoint(
        operationId: 'storeClinic',
        title: 'Create a clinic',
        description: 'Creates a new clinic.'
    )]
    public function __invoke(UpsertClinicRequest $request, StoreClinicAction $storeClinicAction): JsonResponse
    {
        $clinic = $storeClinicAction->execute($request->toDto());

        return ClinicResource::make($clinic)
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }
}
