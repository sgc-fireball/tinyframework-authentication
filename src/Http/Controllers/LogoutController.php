<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\Http\Controllers;

use TinyFramework\Authentication\Events\LogoutEvent;
use TinyFramework\Http\Request;
use TinyFramework\Http\RequestInterface;
use TinyFramework\Http\Response;

class LogoutController
{
    protected string $redirectTo = '/';

    public function logout(RequestInterface $request): Response
    {
        event(new LogoutEvent($request));
        $request->session()
            ->forget('user_id')
            ->regenerate(true);
        return Response::redirect($this->redirectTo);
    }
}
