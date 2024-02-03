<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\Http\Middleware;

use Closure;
use TinyFramework\Authentication\Models\User;
use TinyFramework\Http\Middleware\MiddlewareInterface;
use TinyFramework\Http\Request;
use TinyFramework\Http\RequestInterface;
use TinyFramework\Http\Response;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(RequestInterface $request, Closure $next, ...$parameters): Response
    {
        $class = '\\' . config('auth.model');
        if (!$request->user() && $userId = $request->session()->get('user_id')) {
            /** @var User|null $user */
            if ($userId && $user = $class::query()->where('id', $userId)->first()) {
                $request->user($user);
            }
        }
        return $next($request);
    }
}
