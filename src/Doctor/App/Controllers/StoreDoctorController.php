<?php

declare(strict_types=1);

namespace Lightit\Doctor\App\Controllers;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Lightit\Doctor\App\Requests\UpsertDoctorRequest;
use Lightit\Doctor\App\Resources\DoctorResource;
use Lightit\Doctor\Domain\Actions\StoreDoctorAction;

#[Group('Doctors')]
final readonly class StoreDoctorController
{
    #[Endpoint(
        operationId: 'storeDoctor',
        title: 'Create a doctor',
        description: 'Creates a new doctor.'
    )]
    public function __invoke(UpsertDoctorRequest $request, StoreDoctorAction $storeDoctorAction): JsonResponse
    {
        $doctor = $storeDoctorAction->execute($request->toDto());

        return DoctorResource::make($doctor)
            ->response()
            ->setStatusCode(JsonResponse::HTTP_CREATED);
    }
}
