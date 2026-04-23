<?php

declare(strict_types=1);

namespace Lightit\Clinic\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Lightit\Clinic\Domain\DataTransferObjects\ClinicDto;
use Lightit\Clinic\Domain\Models\Clinic;

class UpsertClinicRequest extends FormRequest
{
    public const string NAME = 'name';

    public const string ADDRESS = 'address';

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $clinic = $this->route('clinic');

        return [
            self::NAME => [
                'required',
                'string',
                'min:4',
                'max:80',
                'regex:/^[\pL][\pL\s\'.-]*$/u',
                Rule::unique(Clinic::class, 'name')->ignore($clinic?->id),
            ],
            self::ADDRESS => [
                'required',
                'string',
                'min:8',
                'max:255',
                'regex:/^[\pL\pN\s,.\-#\/]+$/u',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            self::NAME . '.regex' => 'The clinic name must contain only letters, spaces, apostrophes, dots, or hyphens.',
            self::NAME . '.unique' => 'A clinic with this name already exists.',
            self::ADDRESS . '.regex' => 'The clinic address must include valid characters and contain at least one letter and one number.',
        ];
    }

    public function toDto(): ClinicDto
    {
        return new ClinicDto(
            name: $this->string(self::NAME)->toString(),
            address: $this->string(self::ADDRESS)->toString(),
        );
    }
}
