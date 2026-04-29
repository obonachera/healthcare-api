<?php

declare(strict_types=1);

namespace Lightit\Doctor\Domain\Actions;

use Lightit\Doctor\Domain\DataTransferObjects\DoctorDto;
use Lightit\Doctor\Domain\Models\Doctor;

class StoreDoctorAction
{
    public function execute(DoctorDto $doctorDto): Doctor
    {
        $doctor = new Doctor();
        $doctor->name = $doctorDto->name;
        $doctor->saveOrFail();

        $doctor->clinics()->sync($doctorDto->clinics);

        return $doctor->load(
            ['clinics' => fn (\Illuminate\Database\Eloquent\Relations\BelongsToMany $q) => $q->withCount('doctors')]
        );
    }
}
