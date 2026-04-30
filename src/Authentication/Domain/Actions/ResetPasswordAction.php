<?php

declare(strict_types=1);

namespace Lightit\Authentication\Domain\Actions;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Lightit\Authentication\Domain\DataTransferObjects\ResetPasswordDto;
use Lightit\Authentication\Domain\Exceptions\ResetPasswordException;
use Lightit\Patients\Domain\Models\Patient;

final readonly class ResetPasswordAction
{
    /**
     * @throws ResetPasswordException
     */
    public function execute(ResetPasswordDto $dto): void
    {
        $broker = Password::broker();

        /** @var Patient|null $user */
        $user = $broker->getUser(['email' => $dto->email]);

        if (! $user || ! $broker->tokenExists($user, $dto->token)) {
            throw new ResetPasswordException();
        }

        $user->password = Hash::make($dto->password);
        $user->saveOrFail();

        $broker->deleteToken($user);
    }
}
