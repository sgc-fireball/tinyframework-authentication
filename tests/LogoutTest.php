<?php

declare(strict_types=1);

use TinyFramework\Helpers\Uuid;
use TinyFramework\Http\Request;
use TinyFramework\Http\URL;
use TinyFramework\PHPUnit\HttpTestCase;

class LogoutTest extends HttpTestCase
{
    public function testLogout(): void
    {
        $sessionId = Uuid::v4();
        $email = 'user+' . Uuid::v4() . '@example.de';
        $password = 'Ee1!' . Uuid::v4();

        $class = config('auth.model');
        /** @var \TinyFramework\Database\BaseModel $user */
        $user = new $class();
        $user->email = $email;
        $user->password = hasher()->hash($password);
        $user->save();

        $this->session
            ->open($sessionId)
            ->set('user_id', $user->id);
        $this->assertEquals($user->id, $this->session->get('user_id'));

        $request = (new Request())
            ->method('GET')
            ->url(new URL('https://localhost/logout'))
            ->header('referer', 'https://localhost/profile')
            ->cookie(config('session.cookie'), $sessionId)
            ->session($this->session);
        $response = $this->kernel->handle($request);
        $this->assertEquals(302, $response->code());
        $this->assertEquals('/', $response->header('location'));
        // check old session
        $this->session->open($sessionId);
        $this->assertEquals(null, $this->session->get('user_id'));
        // check new session
        $this->session->open($this->session->getId());
        $this->assertEquals(null, $this->session->get('user_id'));
        $user->delete();
    }
}
