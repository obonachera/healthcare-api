<?php

declare(strict_types=1);

namespace Lightit\Doctor\Domain\Actions;

use Lightit\Doctor\Domain\DataTransferObjects\DoctorDto;
use Lightit\Doctor\Domain\Models\Doctor;

class UpdateDoctorAction
{
    public function execute(Doctor $doctor, DoctorDto $doctorDto): Doctor
    {
        $doctor->name = $doctorDto->name;
        $doctor->saveOrFail();

        $doctor->clinics()->sync($doctorDto->clinics);

        return $doctor->load(['clinics' => fn (\Illuminate\Database\Eloquent\Builder $q) => $q->withCount('doctors')]);
    }
}
