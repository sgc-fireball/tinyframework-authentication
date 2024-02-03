<?php

declare(strict_types=1);

namespace TinyFramework\Authentication\ServiceProvider;

use TinyFramework\Authentication\Migrations\Migration_1_Users;
use TinyFramework\Core\ConfigInterface;
use TinyFramework\Http\Router;
use TinyFramework\ServiceProvider\ServiceProviderAwesome;
use TinyFramework\Template\Blade;

class AuthServiceProvider extends ServiceProviderAwesome
{
    public function register(): void
    {
        /** @var ConfigInterface $config */
        $config = $this->container->get(ConfigInterface::class);
        $config->load('auth', __DIR__ . '/../Config/auth.php');
    }

    public function boot(): void
    {
        /** @var Router $router */
        $router = $this->container->get(Router::class);
        $router->load(__DIR__ . '/../Routes/web.php');

        /** @var Blade $blade */
        $blade = $this->container->get(Blade::class);
        $blade->addNamespaceDirectory('auth', __DIR__ . '/../../resources/views');

        $this->container->tag('migration', Migration_1_Users::class);
    }
}
