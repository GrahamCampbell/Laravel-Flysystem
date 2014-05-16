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

namespace GrahamCampbell\Flysystem\Managers;

use Illuminate\Config\Repository;
use GrahamCampbell\Flysystem\Filesystem\ConnectionFactory;
use GrahamCampbell\Manager\Managers\AbstractManager;

/**
 * This is the flysystem manager class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class FlysystemManager extends AbstractManager
{
    /**
     * The connection factory instance.
     *
     * @var \GrahamCampbell\Flysystem\Filesystem\ConnectionFactory
     */
    protected $factory;

    /**
     * Create a new flysystem manager instance.
     *
     * @param  \Illuminate\Config\Repository   $config
     * @param  \GrahamCampbell\Flysystem\Filesystem\ConnectionFactory  $factory
     * @return void
     */
    public function __construct(Repository $config, ConnectionFactory $factory)
    {
        $this->config = $config;
        $this->factory = $factory;
    }

    /**
     * Create the connection instance.
     *
     * @param  array  $config
     * @return string
     */
    protected function createConnection(array $config)
    {
        return $this->factory->make($config, $this);
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected function getConfigName()
    {
        return 'graham-campbell/flysystem';
    }

    /**
     * Get the configuration for a connection.
     *
     * @param  string  $name
     * @return array
     */
    public function getConnectionConfig($name)
    {
        $name = $name ?: $this->getDefaultConnection();

        $connections = $this->config->get($this->getConfigName().'::connections');

        if (is_null($config = array_get($connections, $name))) {
            throw new \InvalidArgumentException("Adapter [$name] not configured.");
        }

        if (!is_null($cache = array_get($config, 'cache'))) {
            $config['cache'] = $this->getCacheConfig($cache);
        }

        $config['name'] = $name;

        return $config;
    }

    /**
     * Get the cache configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function getCacheConfig($name)
    {
        $cache = $this->config->get($this->getConfigName().'::cache');

        if (is_null($config = array_get($cache, $name))) {
            throw new \InvalidArgumentException("Cache [$name] not configured.");
        }

        $config['name'] = $name;

        return $config;
    }

    /**
     * Get the factory instance.
     *
     * @return \GrahamCampbell\Flysystem\Filesystem\ConnectionFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }
}
