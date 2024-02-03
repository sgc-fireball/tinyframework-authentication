<?php

declare(strict_types=1);

use TinyFramework\Helpers\Uuid;
use TinyFramework\Http\Request;
use TinyFramework\Http\URL;
use TinyFramework\PHPUnit\HttpTestCase;

class LoginTest extends HttpTestCase
{
    public function loginDataProvider(): array
    {
        return [
            [null, '/'],
            ['/test', '/test'],
            ['http://localhost/test', 'http://localhost/test'],
        ];
    }

    /**
     * @dataProvider loginDataProvider
     */
    public function testLogin(string|null $redirect, string $target = '/'): void
    {
        $email = 'user+' . Uuid::v4() . '@example.de';
        $password = 'Ee1!' . Uuid::v4();

        $class = config('auth.model');
        /** @var \TinyFramework\Database\BaseModel $user */
        $user = new $class();
        $user->email = $email;
        $user->password = hasher()->hash($password);
        $user->save();

        $url = (new URL('https://localhost/login'))->query(['redirect' => $redirect]);
        $request = (new Request())
            ->url($url)
            ->get(['redirect' => $redirect])
            ->session($this->session);

        $response = $this->kernel->handle($request);
        $this->assertEquals(200, $response->code());
        if ($redirect) {
            $redirectContains = sprintf('redirect=%s', urlencode($redirect));
            $this->assertStringContainsString($redirectContains, $response->content());
        }

        $this->session->open($this->session->getId());
        $request = $request->method('POST')
            ->header('referer', 'https://localhost/login')
            ->cookie(config('session.cookie'), $this->session->getId())
            ->get(['redirect' => $redirect])
            ->post([
                'email' => $email,
                'password' => $password,
                'csrf-token' => $this->session->get('csrf-token'),
            ]);
        $response = $this->kernel->handle($request);
        $this->assertEquals(302, $response->code());
        $this->assertEquals($target, $response->header('location'));
        $this->session->open($this->session->getId());
        $this->assertEquals($user->id, $this->session->get('user_id'));
    }

    public function testLoginFailed(): void
    {
        $redirect = 'https://' . bin2hex(random_bytes(16)) . '.de/test?query=1';
        $redirectContains = sprintf('redirect=%s', urlencode($redirect));

        $url = (new URL('https://localhost/login'))->query(['redirect' => $redirect]);
        $request = (new Request())
            ->url($url)
            ->get(['redirect' => $redirect])
            ->session($this->session);

        $response = $this->kernel->handle($request);
        $this->assertEquals(200, $response->code());
        $this->assertStringContainsString($redirectContains, $response->content());

        $this->session->open($this->session->getId());
        $request = $request->method('POST')
            ->header('referer', $url->__toString())
            ->cookie(config('session.cookie'), $this->session->getId())
            ->get(['redirect' => $redirect])
            ->post([
                'email' => 'user+' . Uuid::v4() . '@example.de',
                'password' => 'Ee1!' . Uuid::v4(),
                'csrf-token' => $this->session->get('csrf-token'),
            ]);
        $response = $this->kernel->handle($request);
        $this->assertEquals(302, $response->code());
        $this->assertEquals($url->__toString(), $response->header('location'));

        $this->session->open($this->session->getId());
        $url = new URL($response->header('location'));
        parse_str($url->query(), $query);
        $this->assertIsArray($query);
        $this->assertArrayHasKey('redirect', $query);
        $this->assertEquals($query['redirect'], $redirect);
        $request = (new Request())
            ->method('GET')
            ->url($url)
            ->session($this->session)
            ->header('referer', $url->__toString())
            ->get($query)
            ->cookie(config('session.cookie'), $this->session->getId());
        $response = $this->kernel->handle($request);
        $this->assertEquals(200, $response->code());
        $this->assertStringContainsString($redirectContains, $response->content());
    }
}
