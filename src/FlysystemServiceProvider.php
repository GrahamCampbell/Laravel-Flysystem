<?php

declare(strict_types=1);

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
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
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
        $source = realpath($raw = __DIR__.'/../config/flysystem.php') ?: $raw;

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('flysystem.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('flysystem');
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
        $this->registerAdapterFactory();
        $this->registerCacheFactory();
        $this->registerFlysystemFactory();
        $this->registerManager();
        $this->registerBindings();
    }

    /**
     * Register the adapter factory class.
     *
     * @return void
     */
    protected function registerAdapterFactory()
    {
        $this->app->singleton('flysystem.adapterfactory', function () {
            return new AdapterFactory();
        });

        $this->app->alias('flysystem.adapterfactory', AdapterFactory::class);
    }

    /**
     * Register the cache factory class.
     *
     * @return void
     */
    protected function registerCacheFactory()
    {
        $this->app->singleton('flysystem.cachefactory', function (Container $app) {
            $cache = $app['cache'];

            return new CacheFactory($cache);
        });

        $this->app->alias('flysystem.cachefactory', CacheFactory::class);
    }

    /**
     * Register the flysystem factory class.
     *
     * @return void
     */
    protected function registerFlysystemFactory()
    {
        $this->app->singleton('flysystem.factory', function (Container $app) {
            $adapter = $app['flysystem.adapterfactory'];
            $cache = $app['flysystem.cachefactory'];

            return new FlysystemFactory($adapter, $cache);
        });

        $this->app->alias('flysystem.factory', FlysystemFactory::class);
    }

    /**
     * Register the manager class.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton('flysystem', function (Container $app) {
            $config = $app['config'];
            $factory = $app['flysystem.factory'];

            return new FlysystemManager($config, $factory);
        });

        $this->app->alias('flysystem', FlysystemManager::class);
    }

    /**
     * Register the bindings.
     *
     * @return void
     */
    protected function registerBindings()
    {
        $this->app->bind('flysystem.connection', function (Container $app) {
            $manager = $app['flysystem'];

            return $manager->connection();
        });

        $this->app->alias('flysystem.connection', Filesystem::class);
        $this->app->alias('flysystem.connection', FilesystemInterface::class);
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
