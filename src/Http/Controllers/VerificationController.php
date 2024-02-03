<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\Http\Controllers;

use TinyFramework\Authentication\Events\VerificationEvent;
use TinyFramework\Authentication\Models\User;
use TinyFramework\Http\RequestInterface;
use TinyFramework\Http\Response;

class VerificationController
{
    private string $redirectTo = '/';

    public function __construct()
    {
        $this->redirectTo = route('auth.login');
    }

    public function verification(RequestInterface $request, string $code): Response
    {
        $class = '\\' . config('auth.model');
        /** @var User $user */
        $user = $class::query()->where('verification_key', '=', $code)->first();
        if (!$user) {
            usleep(mt_rand(50_000, 150_000));
            return Response::redirect($this->redirectTo);
        }
        $user->verification_key = null;
        $user->verification_at = now();
        $user->save();
        $request->user($user);
        event(new VerificationEvent($request));
        return Response::redirect($this->redirectTo);
    }
}
