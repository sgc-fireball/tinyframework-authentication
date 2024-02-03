<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\Http\Controllers;

use TinyFramework\Authentication\Http\Requests\PasswordResetRequest;
use TinyFramework\Authentication\Models\User;
use TinyFramework\Http\Request;
use TinyFramework\Http\Response;

class PasswordResetController
{
    protected string $redirectTo;

    public function __construct()
    {
        $this->redirectTo = route('auth.login');
    }

    public function form(string $code): Response
    {
        return view('auth@password-reset', compact('code'));
    }

    public function reset(PasswordResetRequest $request, string $code): Response
    {
        if (!$request->validate()) {
            return Response::back()->withErrors($request->getErrorBag());
        }

        $class = '\\' . config('auth.model');
        /** @var User $user */
        $user = $class::query()->where('password_reset_key', '=', $code)->first();
        if ($user) {
            $user->verification_key = null;
            $user->password_reset_key = null;
            $user->password_reset_at = now();
            $user->verification_at ??= $user->password_reset_at;
            $user->password = hasher()->hash($request->post('password'));
            $user->save();
        } else {
            usleep(mt_rand(50_000, 150_000));
        }
        return Response::redirect($this->redirectTo);
    }
}
