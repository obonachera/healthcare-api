<?php

declare(strict_types=1);

namespace Lightit\Doctor\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorAvailabilityRequest extends FormRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:Y-m-d'],
        ];
    }
}
