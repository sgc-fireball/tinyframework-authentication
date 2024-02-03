<?php

declare(strict_types=1);

use TinyFramework\Helpers\Uuid;
use TinyFramework\Http\Request;
use TinyFramework\Http\URL;
use TinyFramework\PHPUnit\HttpTestCase;

class PasswordResetTest extends HttpTestCase
{
    public function testPasswordReset(): void
    {
        $email = 'user+' . Uuid::v4() . '@example.de';
        $passwordResetKey = Uuid::v4();
        $password = 'Ee1!' . Uuid::v4();

        $class = config('auth.model');
        /** @var \TinyFramework\Authentication\Models\User $user */
        $user = new $class();
        $user->email = $email;
        $user->password_reset_key = $passwordResetKey;
        $user->save();
        $user->reload();

        $this->assertNull($user->verification_key);
        $this->assertNull($user->verification_at);
        $this->assertNotNull($user->password_reset_key);
        $this->assertNull($user->password_reset_at);

        $request = (new Request())
            ->url(new URL('https://localhost/password-reset/' . $user->password_reset_key))
            ->session($this->session);
        $response = $this->kernel->handle($request);
        $this->assertEquals(200, $response->code());

        $this->session->open($this->session->getId());
        $request = $request
            ->method('POST')
            ->header('referer', 'https://localhost/password-reset/' . $user->password_reset_key)
            ->cookie(config('session.cookie'), $this->session->getId())
            ->post([
                'password' => $password,
                'password_confirmed' => $password,
                'csrf-token' => $this->session->get('csrf-token'),
            ]);
        $response = $this->kernel->handle($request);
        $this->assertEquals(302, $response->code());
        $this->assertEquals(route('auth.login'), $response->header('location'));

        $user->reload();
        $this->assertEmpty($user->verification_key);
        $this->assertEmpty($user->password_reset_key);
        $this->assertNotNull($user->password_reset_at);
        $this->assertNotNull($user->verification_at);
        $this->assertTrue(hasher()->verify($password, $user->password));
    }
}
