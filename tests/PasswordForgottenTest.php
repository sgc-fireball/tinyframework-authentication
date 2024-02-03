<?php

declare(strict_types=1);

use TinyFramework\Helpers\Uuid;
use TinyFramework\Http\Request;
use TinyFramework\Http\URL;
use TinyFramework\PHPUnit\HttpTestCase;

class PasswordForgottenTest extends HttpTestCase
{
    public function testPasswordForgotten(): void
    {
        $email = 'user+' . Uuid::v4() . '@example.de';
        $password = 'Ee1!' . Uuid::v4();

        $class = config('auth.model');
        /** @var \TinyFramework\Authentication\Models\User $user */
        $user = new $class();
        $user->email = $email;
        $user->password = hasher()->hash($password);
        $user->save();
        $user->reload();

        $this->assertNull($user->password_reset_key);
        $this->assertNull($user->password_reset_at);

        $request = (new Request())
            ->url(new URL('https://localhost/password-forgotten'))
            ->session($this->session);
        $response = $this->kernel->handle($request);
        $this->assertEquals(200, $response->code());

        $this->session->open($this->session->getId());
        $request = $request
            ->method('POST')
            ->header('referer', 'https://localhost/password-forgotten')
            ->cookie(config('session.cookie'), $this->session->getId())
            ->post([
                'email' => $user->email,
                'csrf-token' => $this->session->get('csrf-token'),
            ]);
        $response = $this->kernel->handle($request);
        $this->assertEquals(302, $response->code());
        $this->assertEquals(route('auth.login'), $response->header('location'));

        $user->reload();
        $this->assertNotEmpty($user->password_reset_key);
        $this->assertNull($user->password_reset_at);

        //@TODO fake MailerInterface and catch send method
    }
}
