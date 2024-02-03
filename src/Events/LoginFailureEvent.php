<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\Events;

use TinyFramework\Auth\Authenticatable;
use TinyFramework\Event\EventAwesome;
use TinyFramework\Http\Request;
use TinyFramework\Http\RequestInterface;

class LoginFailureEvent extends EventAwesome
{
    public function __construct(public readonly RequestInterface $request)
    {
    }
}
