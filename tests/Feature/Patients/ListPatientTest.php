<?php

declare(strict_types=1);

namespace Tests\Feature\Patients;

use Database\Factories\PatientFactory;
use Lightit\Patients\App\Controllers\ListPatientController;
use function Pest\Laravel\getJson;

describe('patients', function (): void {
    /** @see ListPatientController */
    it('can list patients successfully', function (): void {
        $patients = PatientFactory::new()
            ->createMany(5);

        getJson(url('/api/patients'))
            ->assertSuccessful()
            ->assertJsonCount(5, 'data');
    });
});
