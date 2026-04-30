<?php

declare(strict_types=1);

namespace Tests\Feature\Patients;

use Database\Factories\PatientFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\Fluent\AssertableJson;
use Lightit\Patients\App\Controllers\UpdatePatientController;
use Lightit\Patients\App\Resources\PatientResource;
use Lightit\Patients\Domain\Models\Patient;
use Tests\RequestFactories\StorePatientRequestFactory;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\putJson;

beforeEach(fn () => Notification::fake());

describe('patients', function (): void {
    /** @see UpdatePatientController */
    it('can update a patient successfully', function (): void {
        $patient = PatientFactory::new()->createOne([
            'name' => 'old',
        ]);

        $data = StorePatientRequestFactory::new()->create([
            'name' => 'Updated',
        ]);

        $response = putJson(url("/api/patients/$patient->id"), $data);

        $patient = Patient::query()
            ->where('name', $data['name'])
            ->firstOrFail();

        $response
            ->assertOk()
            ->assertJson(
                fn (AssertableJson $json): AssertableJson =>
                $json->has(
                    'data',
                    fn (AssertableJson $json): AssertableJson => $json->whereAll(
                        PatientResource::make($patient)->resolve()
                    )
                )
            );

        assertDatabaseHas('patients', [
            'name' => $data['name'],
            'email' => $data['email_address'],
        ]);

        expect(Hash::check('>e$pV4chNFcJoAB%X#{', $patient->password))->toBeTrue();
    });

    it('cannot update a patient with invalid data', function (): void {
        $existingPatient = PatientFactory::new()->createOne();

        $data = [
            'name' => '',
            'email_address' => 'not-an-email',
            'password' => 'short',
        ];

        $response = putJson(url("/api/patients/$existingPatient->id"), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email_address', 'password'], 'error.fields');
    });
});
