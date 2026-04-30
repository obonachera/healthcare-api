<?php

declare(strict_types=1);

namespace Tests\Feature\Patients;

use Database\Factories\PatientFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\AssertableJson;
use Lightit\Patients\App\Controllers\StorePatientController;
use Lightit\Patients\App\Resources\PatientResource;
use Lightit\Patients\Domain\Models\Patient;
use Tests\RequestFactories\StorePatientRequestFactory;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\postJson;

function getLongName(): string
{
    return Str::repeat(string: 'name', times: random_int(min: 30, max: 50));
}

function getATakenEmail(): string
{
    $takenEmail = 'taken@example.com';
    PatientFactory::new()->createOne(['email' => $takenEmail]);

    return $takenEmail;
}

dataset(name: 'validation-rules', dataset: [
    'name is required' => ['name', ''],
    'name be a string' => ['name', ['array']],
    'name not too short' => ['name', 'ams'],
    'name not too long' => ['name', getLongName()],

    'email is required' => ['email_address', ''],
    'email be valid' => ['email_address', 'esthernjerigmail.com'],
    'email not too long' => ['email_address', fn (): string => getLongName() . '@gmail.com'],
    'email be unique' => ['email_address', fn (): string => getATakenEmail()],

    'password is required' => ['password', ''],
    'password be >=8 chars' => ['password', 'Hf^gsg8'],
    'password be uncompromised' => ['password', 'password'],
    'password not too long' => ['password', fn (): string => getLongName()],
]);

describe('patients', function (): void {
    /** @see StorePatientController */
    it(description: 'can create a patient successfully', closure: function (): void {
        $data = StorePatientRequestFactory::new()->create([
            'password' => '>e$pV4chNFcJoAB%X#{',
        ]);

        $response = postJson(url('/api/patients'), $data);

        $patient = Patient::query()
            ->where('email', $data['email_address'])
            ->firstOrFail();

        $response
            ->assertCreated()
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

    it(description: 'cannot create a patient with an already registered email', closure: function (): void {
        $existingPatient = PatientFactory::new()->createOne();

        $data = StorePatientRequestFactory::new()->create([
            'email_address' => $existingPatient->email,
        ]);

        $response = postJson(url('/api/patients'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email_address'], 'error.fields');

        assertDatabaseMissing('patients', [
            'name' => $data['name'],
            'email' => $data['email_address'],
        ]);
    });

    it('cannot create a patient with invalid data', closure: function (string $field, string|array $value): void {
        $data = StorePatientRequestFactory::new()->create();

        $response = postJson(url('/api/patients'), [...$data, $field => $value]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors([$field], 'error.fields');
    })->with('validation-rules');
});
