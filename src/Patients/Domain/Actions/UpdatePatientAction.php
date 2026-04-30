<?php

declare(strict_types=1);

namespace Lightit\Patients\Domain\Actions;

use Lightit\Patients\Domain\DataTransferObjects\PatientDto;
use Lightit\Patients\Domain\Models\Patient;

class UpdatePatientAction
{
    public function execute(Patient $patient, PatientDto $patientDto): Patient
    {
        $patient->name = $patientDto->name;
        $patient->email = $patientDto->emailAddress;
        $patient->password = $patientDto->password;

        $patient->saveOrFail();

        return $patient;
    }
}
