<?php

/**
 * This file is part of Laravel Flysystem by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
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
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
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
        $this->package('graham-campbell/flysystem');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFlysystem();
    }

    /**
     * Register the flysystem class.
     *
     * @return void
     */
    protected function registerFlysystem()
    {
        $this->app->bindShared('flysystem', function ($app) {
            $config = $app['config'];
            $factory = new Connectors\ConnectionFactory();

            return new Managers\FlysystemManager($config, $factory);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'flysystem'
        );
    }
}
