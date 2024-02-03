<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\Events;

use TinyFramework\Auth\Authenticatable;
use TinyFramework\Event\EventAwesome;
use TinyFramework\Http\Request;
use TinyFramework\Http\RequestInterface;

class RegistrationEvent extends EventAwesome
{
    public function __construct(
        public readonly RequestInterface $request,
        public readonly Authenticatable $user
    ) {
    }
}
