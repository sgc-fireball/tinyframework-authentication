<?php

declare(strict_types=1);

use TinyFramework\Helpers\Uuid;
use TinyFramework\Http\Request;
use TinyFramework\Http\URL;
use TinyFramework\PHPUnit\HttpTestCase;

class RegisterTest extends HttpTestCase
{
    public function testRegister(): void
    {
        $email = 'user+' . Uuid::v4() . '@example.de';
        $password = 'Ee1!' . Uuid::v4();

        $request = (new Request())
            ->url(new URL('https://localhost/register'))
            ->session($this->session);
        $response = $this->kernel->handle($request);
        $this->assertEquals(200, $response->code());

        $this->session->open($this->session->getId());
        $request = $request->method('POST')
            ->header('referer', 'https://localhost/register')
            ->cookie(config('session.cookie'), $this->session->getId())
            ->post([
                'email' => $email,
                'password' => $password,
                'password_confirmed' => $password,
                'csrf-token' => $this->session->get('csrf-token'),
            ]);

        $response = $this->kernel->handle($request);
        $this->assertEquals(302, $response->code());
        $this->assertEquals('/', $response->header('location'));

        $class = config('auth.model');
        $user = $class::query()->where('email', '=', $email)->first();
        $this->assertNotNull($user);
        $this->assertTrue(hasher()->verify($password, $user->password));
        $this->assertNotNull($user->verification_key);
        $this->assertNull($user->verification_at);
        $this->assertNull($user->password_reset_key);
        $this->assertNull($user->password_reset_at);
    }
}
