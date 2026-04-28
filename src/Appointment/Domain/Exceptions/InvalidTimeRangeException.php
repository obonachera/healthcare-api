<?php

declare(strict_types=1);

namespace Lightit\Appointment\Domain\Exceptions;

use Illuminate\Http\JsonResponse;
use Lightit\Shared\App\Exceptions\Http\HttpException;

class InvalidTimeRangeException extends HttpException
{
    #[\Override]
    protected int $status = JsonResponse::HTTP_UNPROCESSABLE_ENTITY;

    #[\Override]
    protected string $errorCode = 'invalid_time_range';

    #[\Override]
    protected $message = 'Start time must be before end time.';
}
