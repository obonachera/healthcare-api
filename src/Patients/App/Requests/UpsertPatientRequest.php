<?php

declare(strict_types=1);

namespace Lightit\Patients\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Email;
use Illuminate\Validation\Rules\Password;
use Lightit\Patients\Domain\DataTransferObjects\PatientDto;
use Lightit\Patients\Domain\Models\Patient;

class UpsertPatientRequest extends FormRequest
{
    public const string NAME = 'name';

    public const string EMAIL = 'email_address';

    public const string PASSWORD = 'password';

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            self::NAME => ['required', 'string', 'min:4', 'max:80'],
            self::EMAIL => [
                'required',
                'max:100',
                Email::default(),
                Rule::unique(Patient::class, 'email'),
            ],
            self::PASSWORD => [
                'required',
                Password::default(),
                'confirmed',
            ],
        ];
    }

    public function toDto(): PatientDto
    {
        return new PatientDto(
            name: $this->string(self::NAME)->toString(),
            emailAddress: $this->string(self::EMAIL)->toString(),
            password: $this->string(self::PASSWORD)->toString(),
        );
    }
}
