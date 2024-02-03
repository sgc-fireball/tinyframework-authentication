<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\Http\Requests;

use TinyFramework\Http\RequestValidator;

class PasswordResetRequest extends RequestValidator
{
    public function rules(): array
    {
        return [
            'password' => 'required|password|confirmed',
        ];
    }
}
