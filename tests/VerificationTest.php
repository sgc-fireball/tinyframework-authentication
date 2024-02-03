<?php

declare(strict_types=1);

use TinyFramework\Helpers\Uuid;
use TinyFramework\Http\Request;
use TinyFramework\Http\URL;
use TinyFramework\PHPUnit\HttpTestCase;

class VerificationTest extends HttpTestCase
{
    public function testVerification(): void
    {
        $email = 'user+' . Uuid::v4() . '@example.de';
        $verificationKey = Uuid::v4();

        $class = config('auth.model');
        /** @var \TinyFramework\Authentication\Models\User $user */
        $user = new $class();
        $user->email = $email;
        $user->verification_key = $verificationKey;
        $user->save();
        $user->reload();

        $this->assertNotNull($user->verification_key);
        $this->assertNull($user->verification_at);

        $request = (new Request())
            ->url(new URL('https://localhost/verification/' . $user->verification_key))
            ->session($this->session);
        $response = $this->kernel->handle($request);
        $this->assertEquals(302, $response->code());
        $this->assertEquals(route('auth.login'), $response->header('location'));

        $user->reload();
        $this->assertNull($user->verification_key);
        $this->assertNotNull($user->verification_at);
    }
}
