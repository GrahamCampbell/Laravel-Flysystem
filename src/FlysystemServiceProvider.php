<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Flysystem;

use GrahamCampbell\Flysystem\Adapters\ConnectionFactory as AdapterFactory;
use GrahamCampbell\Flysystem\Cache\ConnectionFactory as CacheFactory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

/**
 * This is the flysystem service provider class.
 *
 * @author Graham Campbell <graham@alt-three.com>
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

        if (class_exists('Illuminate\Foundation\Application', false)) {
            $this->publishes([$source => config_path('flysystem.php')]);
        }

        $this->mergeConfigFrom($source, 'flysystem');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAdapterFactory($this->app);
        $this->registerCacheFactory($this->app);
        $this->registerFlysystemFactory($this->app);
        $this->registerManager($this->app);
        $this->registerBindings($this->app);
    }

    /**
     * Register the adapter factory class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerAdapterFactory(Application $app)
    {
        $app->singleton('flysystem.adapterfactory', function () {
            return new AdapterFactory();
        });

        $app->alias('flysystem.adapterfactory', AdapterFactory::class);
    }

    /**
     * Register the cache factory class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerCacheFactory(Application $app)
    {
        $app->singleton('flysystem.cachefactory', function ($app) {
            $cache = $app['cache'];

            return new CacheFactory($cache);
        });

        $app->alias('flysystem.cachefactory', CacheFactory::class);
    }

    /**
     * Register the flysystem factory class.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerFlysystemFactory(Application $app)
    {
        $app->singleton('flysystem.factory', function ($app) {
            $adapter = $app['flysystem.adapterfactory'];
            $cache = $app['flysystem.cachefactory'];

            return new FlysystemFactory($adapter, $cache);
        });

        $app->alias('flysystem.factory', FlysystemFactory::class);
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

        $app->alias('flysystem', FlysystemManager::class);
    }

    /**
     * Register the bindings.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function registerBindings(Application $app)
    {
        $app->bind('flysystem.connection', function ($app) {
            $manager = $app['flysystem'];

            return $manager->connection();
        });

        $app->alias('flysystem.connection', Filesystem::class);
        $app->alias('flysystem.connection', FilesystemInterface::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'flysystem.adapterfactory',
            'flysystem.cachefactory',
            'flysystem.factory',
            'flysystem',
            'flysystem.connection',
        ];
    }
}
