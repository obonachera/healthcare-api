<?php

declare(strict_types=1);

namespace Lightit\Clinic\Domain\Actions;

use Lightit\Clinic\Domain\DataTransferObjects\ClinicDto;
use Lightit\Clinic\Domain\Models\Clinic;

class UpdateClinicAction
{
    public function execute(Clinic $clinic, ClinicDto $clinicDto): Clinic
    {
        $clinic->name = $clinicDto->name;
        $clinic->address = $clinicDto->address;

        $clinic->saveOrFail();

        $clinic->doctors()->sync($clinicDto->doctors);

        return $clinic->loadCount('doctors');
    }
}
