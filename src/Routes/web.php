<?php

declare(strict_types=1);

use TinyFramework\Authentication\Http\Controllers\LoginController;
use TinyFramework\Authentication\Http\Controllers\LogoutController;
use TinyFramework\Authentication\Http\Controllers\PasswordForgottenController;
use TinyFramework\Authentication\Http\Controllers\PasswordResetController;
use TinyFramework\Authentication\Http\Controllers\RegistrationController;
use TinyFramework\Authentication\Http\Controllers\VerificationController;
use TinyFramework\Authentication\Http\Middleware\AuthMiddleware;
use TinyFramework\Http\Middleware\CsrfMiddlewaere;
use TinyFramework\Http\Middleware\SessionMiddleware;
use TinyFramework\Http\Router;

/** @var \TinyFramework\Http\Router $router */

if (!isset($router)) {
    return;
}

$router->group(
    [
    'middleware' => [
        SessionMiddleware::class,
        CsrfMiddlewaere::class,
    ],
],
    function (Router $router) {
        $router->post('register', RegistrationController::class . '@registration');
        $router->get('register', RegistrationController::class . '@form')
            ->name('auth.registration');

        $router->post('password-forgotten', PasswordForgottenController::class . '@reset');
        $router->get('password-forgotten', PasswordForgottenController::class . '@form')
            ->name('auth.password-forgotten');

        $router->post('login', LoginController::class . '@login');
        $router->get('login', LoginController::class . '@form')
            ->name('auth.login');

        $router->any('logout', LogoutController::class . '@logout')
            ->name('auth.logout');

        $router->group([], function (Router $router) {
            $router->pattern('code', '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}');

            $router->post('verification/{code}', VerificationController::class . '@verification');
            $router->get('verification/{code}', VerificationController::class . '@verification')
                ->name('auth.verification');

            $router->post('password-reset/{code}', PasswordResetController::class . '@reset');
            $router->get('password-reset/{code}', PasswordResetController::class . '@form')
                ->name('auth.password-reset');
        });
    }
);
