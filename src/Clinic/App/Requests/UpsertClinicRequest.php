<?php

declare(strict_types=1);

namespace Lightit\Clinic\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Lightit\Clinic\Domain\DataTransferObjects\ClinicDto;
use Lightit\Clinic\Domain\Models\Clinic;
use Lightit\Doctor\Domain\Models\Doctor;

class UpsertClinicRequest extends FormRequest
{
    public const string NAME = 'name';

    public const string ADDRESS = 'address';

    public const string DOCTORS = 'doctors';

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
<<<<<<< HEAD
        /** @var Clinic|null $clinic */
        $clinic = $this->route('clinic');
=======
        $clinic = $this->route('clinic');
        $clinicId = $clinic instanceof Clinic ? $clinic->id : $clinic;
>>>>>>> 34de92b (feat: update and insert clinics)

        return [
            self::NAME => [
                'required',
                'string',
                'min:4',
                'max:80',
                'regex:/^[\pL][\pL\s\'.-]*$/u',
<<<<<<< HEAD
                Rule::unique(Clinic::class, 'name')->ignore($clinic?->id),
=======
                Rule::unique(Clinic::class, 'name')->ignore($clinicId),
>>>>>>> 34de92b (feat: update and insert clinics)
            ],
            self::ADDRESS => [
                'required',
                'string',
                'min:8',
                'max:255',
                'regex:/^[\pL\pN\s,.\-#\/]+$/u',
            ],
            self::DOCTORS => ['array', Rule::exists(Doctor::class, 'id')],
            self::DOCTORS . '.*' => [Rule::numeric()->integer()],
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
        /** @var array<int> $doctors */
        $doctors = $this->array(self::DOCTORS);

        return new ClinicDto(
            name: $this->string(self::NAME)->toString(),
            address: $this->string(self::ADDRESS)->toString(),
            doctors: $doctors,
        );
    }
}
