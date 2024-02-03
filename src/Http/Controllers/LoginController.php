<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\Http\Controllers;

use TinyFramework\Authentication\Events\LoginFailureEvent;
use TinyFramework\Authentication\Events\LoginSuccessfulEvent;
use TinyFramework\Authentication\Events\LoginTryEvent;
use TinyFramework\Authentication\Http\Requests\LoginRequest;
use TinyFramework\Authentication\Models\User;
use TinyFramework\Http\RequestInterface;
use TinyFramework\Http\Response;

class LoginController
{
    protected string $redirectTo = '/';

    public function form(RequestInterface $request): Response
    {
        return view('auth@login', ['redirect' => $request->get('redirect')]);
    }

    public function login(LoginRequest $request): Response
    {
        if (!$request->validate()) {
            return Response::back()->withErrors($request->getErrorBag());
        }

        event(new LoginTryEvent($request));
        $class = config('auth.model');
        $email = $request->post('email');
        $redirect = $request->get('redirect');

        /** @var User $user */
        $user = $class::query()->where('email', '=', $email)->first();
        if (!$user || !hasher()->verify($request->post('password'), $user->password)) {
            usleep(mt_rand(50_000, 150_000));
            event(new LoginFailureEvent($request));
            return Response::back();
        }
        $request->session()->set('user_id', $user->id)->regenerate();
        event(new LoginSuccessfulEvent($request));
        return Response::redirect($redirect ?: $this->redirectTo);
    }
}
