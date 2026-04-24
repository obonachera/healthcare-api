<?php

declare(strict_types=1);

namespace Lightit\Doctor\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Lightit\Clinic\Domain\Models\Clinic;
use Lightit\Doctor\Domain\DataTransferObjects\DoctorDto;

class UpsertDoctorRequest extends FormRequest
{
    public const string NAME = 'name';

    public const string CLINICS = 'clinics';

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            self::NAME => ['required', 'string', 'min:3', 'max:80'],
            self::CLINICS => ['array', Rule::exists(Clinic::class, 'id')],
            self::CLINICS . '.*' => [Rule::numeric()->integer()],
        ];
    }

    public function toDto(): DoctorDto
    {
        /** @var array<int> $clinics */
        $clinics = $this->array(self::CLINICS);

        return new DoctorDto(
            name: $this->string(self::NAME)->toString(),
            clinics: $clinics,
        );
    }
}
