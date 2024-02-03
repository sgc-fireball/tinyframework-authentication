<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\Http\Controllers;

use TinyFramework\Authentication\Events\RegistrationEvent;
use TinyFramework\Authentication\Http\Requests\RegistrationRequest;
use TinyFramework\Authentication\Models\User;
use TinyFramework\Core\ConfigInterface;
use TinyFramework\Helpers\Uuid;
use TinyFramework\Http\Request;
use TinyFramework\Http\Response;
use TinyFramework\Http\URL;
use TinyFramework\Mail\Mail;
use TinyFramework\Mail\MailerInterface;

class RegistrationController
{
    protected string $model;

    private string $redirectTo = '/';

    public function __construct(
        ConfigInterface $config,
        private readonly MailerInterface $mailer
    ) {
        $this->model = $config->get('auth.model');
    }

    public function form(): Response
    {
        return view('auth@registration');
    }

    public function registration(RegistrationRequest $request): Response
    {
        if (!$request->validate()) {
            return Response::back()->withErrors($request->getErrorBag())->withInput();
        }

        /** @var User $user */
        $user = new $this->model();
        $user->email = $request->post('email');
        $user->password = hasher()->hash($request->post('password'));
        $user->verification_key = Uuid::v4();
        $user->verification_at = null;
        event(new RegistrationEvent($request, $user));
        $user->save();

        $mail = Mail::create()
            ->subject('Confirmation')
            ->text(view('auth@email.confirmation_text', ['user' => $user])->content())
            ->html(view('auth@email.confirmation_html', ['user' => $user])->content())
            ->to($user->email);
        $this->mailer->send($mail);

        $request->session()->set('user_id', $user)->regenerate();
        $request->user($user);

        return Response::redirect($this->redirectTo);
    }
}
