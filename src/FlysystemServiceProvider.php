<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Flysystem;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * This is the flysystem service provider class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class FlysystemServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__.'/../config/flysystem.php');

        $this->publishes([$source => config_path('flysystem.php')]);

        $this->mergeConfigFrom($source, 'flysystem');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFactory($this->app);
        $this->registerManager($this->app);
    }

    /**
     * Register the factory class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerFactory(Application $app)
    {
        $app->singleton('flysystem.factory', function ($app) {
            $adapter = new Adapters\ConnectionFactory();
            $cache = new Cache\ConnectionFactory($app['cache']);

            return new Factories\FlysystemFactory($adapter, $cache);
        });

        $app->alias('flysystem.factory', 'GrahamCampbell\Flysystem\Factories\FlysystemFactory');
    }

    /**
     * Register the manager class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerManager(Application $app)
    {
        $app->singleton('flysystem', function ($app) {
            $config = $app['config'];
            $factory = $app['flysystem.factory'];

            return new FlysystemManager($config, $factory);
        });

        $app->alias('flysystem', 'GrahamCampbell\Flysystem\FlysystemManager');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'flysystem',
            'flysystem.factory',
        ];
    }
}
