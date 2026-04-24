<?php

declare(strict_types=1);

namespace Lightit\Doctor\App\Controllers;

use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;
use Lightit\Doctor\Domain\Models\Doctor;

#[Group('Doctors')]
final readonly class DeleteDoctorController
{
    #[Endpoint(
        operationId: 'deleteDoctor',
        title: 'Delete a doctor',
        description: 'Deletes a doctor by its ID.'
    )]
    public function __invoke(Doctor $doctor): Response
    {
        $doctor->deleteOrFail();

        return response()->noContent();
    }
}
