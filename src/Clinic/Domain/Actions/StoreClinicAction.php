<?php

declare(strict_types=1);

namespace Lightit\Clinic\Domain\Actions;

use Lightit\Clinic\Domain\DataTransferObjects\ClinicDto;
use Lightit\Clinic\Domain\Models\Clinic;

class StoreClinicAction
{
    public function execute(ClinicDto $clinicDto): Clinic
    {
        $clinic = new Clinic();

        $clinic->name = $clinicDto->name;
        $clinic->address = $clinicDto->address;

        $clinic->saveOrFail();

        return $clinic;
    }
}
