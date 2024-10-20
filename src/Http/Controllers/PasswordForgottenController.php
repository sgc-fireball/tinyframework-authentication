<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\Http\Controllers;

use TinyFramework\Authentication\Http\Requests\PasswordForgottenRequest;
use TinyFramework\Authentication\Models\User;
use TinyFramework\Helpers\Uuid;
use TinyFramework\Http\Request;
use TinyFramework\Http\Response;
use TinyFramework\Mail\Mail;
use TinyFramework\Mail\MailerInterface;

class PasswordForgottenController
{
    protected string $redirectTo = '/';

    public function __construct(
        private readonly MailerInterface $mailer
    ) {
        $this->redirectTo = route('auth.login');
    }

    public function form(): Response
    {
        return view('auth@password-forgotten');
    }

    public function reset(PasswordForgottenRequest $request): Response
    {
        if (!$request->validate()) {
            return Response::back()->withErrors($request->getErrorBag())->withInput();
        }

        $email = $request->post('email');
        $class = '\\' . config('auth.model');
        /** @var User $user */
        $user = $class::query()->where('email', '=', $email)->first();
        if (!$user) {
            usleep(mt_rand(250_000, 750_000));
        } else {
            $user->password_reset_key = Uuid::v4();
            $user->password_reset_at = null;
            $user->save();
            $data = compact('user');
            $mail = Mail::create()
                ->subject('Password reset')
                ->text(view('auth@email.password_reset_text', $data)->content())
                ->html(view('auth@email.password_reset_html', $data)->content())
                ->to($user->email);
            $this->mailer->send($mail);
        }
        return Response::redirect($this->redirectTo);
    }
}
