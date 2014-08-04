<?php

/**
 * This file is part of Laravel Flysystem by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://bit.ly/UWsjkb.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Flysystem;

use Illuminate\Support\ServiceProvider;

/**
 * This is the flysystem service provider class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class FlysystemServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('graham-campbell/flysystem', 'graham-campbell/flysystem', __DIR__);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFactory();
        $this->registerManager();
    }

    /**
     * Register the factory class.
     *
     * @return void
     */
    protected function registerFactory()
    {
        $this->app->bindShared('flysystem.factory', function ($app) {
            $adapter = new Adapters\ConnectionFactory();
            $cache = new Cache\ConnectionFactory($app['cache']);

            return new Factories\FlysystemFactory($adapter, $cache);
        });

        $this->app->alias('flysystem.factory', 'GrahamCampbell\Flysystem\Factories\FlysystemFactory');
    }

    /**
     * Register the manager class.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->bindShared('flysystem', function ($app) {
            $config = $app['config'];
            $factory = $app['flysystem.factory'];

            return new FlysystemManager($config, $factory);
        });

        $this->app->alias('flysystem', 'GrahamCampbell\Flysystem\FlysystemManager');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return array(
            'flysystem',
            'flysystem.factory'
        );
    }
}
