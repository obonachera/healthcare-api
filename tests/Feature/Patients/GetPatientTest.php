<?php

declare(strict_types=1);

namespace Tests\Feature\Patients;

use Database\Factories\PatientFactory;
use Illuminate\Testing\Fluent\AssertableJson;
use Lightit\Patients\App\Controllers\GetPatientController;
use Lightit\Patients\App\Resources\PatientResource;
use function Pest\Laravel\getJson;

describe('patients', function (): void {
    /** @see GetPatientController */
    it('retrieves a patient and returns a successful response', function (): void {
        $existingPatient = PatientFactory::new()->createOne();

        getJson("api/patients/$existingPatient->id")
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json): AssertableJson =>
                $json->has(
                    'data',
                    fn (AssertableJson $json): AssertableJson => $json->whereAll(
                        PatientResource::make($existingPatient)->resolve()
                    )
                )
            );
    });

    it('returns a 404 response when patient is not found', function (): void {
        $nonExistentPatientId = 99999;

        getJson("api/patients/{$nonExistentPatientId}")->assertNotFound();
    });
});
