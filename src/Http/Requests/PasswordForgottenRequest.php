<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\Http\Requests;

use TinyFramework\Http\RequestValidator;

class PasswordForgottenRequest extends RequestValidator
{
    public function rules(): array
    {
        return [
            'email' => 'required|string|min:6|max:255|email',
        ];
    }
}
