<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\Events;

use TinyFramework\Auth\Authenticatable;
use TinyFramework\Event\EventAwesome;

class PasswordChangedEvent extends EventAwesome
{
    public function __construct(public readonly Authenticatable $authenticatable)
    {
    }
}
