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

namespace GrahamCampbell\Flysystem\Filesystem;

use League\Flysystem\Filesystem;
use GrahamCampbell\Flysystem\Managers\FlysystemManager;
use GrahamCampbell\Flysystem\Adapters\ConnectionFactory as AdapterFactory;
use GrahamCampbell\Flysystem\Cache\ConnectionFactory as CacheFactory;

/**
 * This is the filesystem connection factory class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class ConnectionFactory
{
    /**
     * The adapter factory instance.
     *
     * @var \GrahamCampbell\Flysystem\Adapters\ConnectionFactory
     */
    protected $adapter;

    /**
     * The cache factory instance.
     *
     * @var \GrahamCampbell\Flysystem\Cache\ConnectionFactory
     */
    protected $cache;

    /**
     * Create a new filesystem connection factory instance.
     *
     * @param  \GrahamCampbell\Flysystem\Adapters\ConnectionFactory  $adapter
     * @param  \GrahamCampbell\Flysystem\Cache\ConnectionFactory  $cache
     * @return void
     */
    public function __construct(AdapterFactory $adapter, CacheFactory $cache)
    {
        $this->adapter = $adapter;
        $this->cache = $cache;
    }

    /**
     * Establish a filesystem connection.
     *
     * @param  array   $config
     * @param  \GrahamCampbell\Flysystem\Managers\FlysystemManager  $manager
     * @return \League\Flysystem\FilesystemInterface
     */
    public function make(array $config, FlysystemManager $manager)
    {
        $adapter = $this->createAdapter($config);

        $cache = $this->createCache($config, $manager);

        return new Filesystem($adapter, $cache);
    }

    /**
     * Establish an adapter connection.
     *
     * @param  array   $config
     * @return \League\Flysystem\AdapterInterface
     */
    public function createAdapter(array $config)
    {
        $config = array_except($config, 'cache');

        return $this->adapter->make($config);
    }

    /**
     * Establish a cache connection.
     *
     * @param  array   $config
     * @param  \GrahamCampbell\Flysystem\Managers\FlysystemManager  $manager
     * @return \League\Flysystem\CacheInterface
     */
    public function createCache(array $config, FlysystemManager $manager)
    {
        if (!is_null($config = array_get($config, 'cache'))) {
            return $this->cache->make($config, $manager);
        }
    }

    /**
     * Get the adapter factory instance.
     *
     * @return \GrahamCampbell\Flysystem\Cache\AdapterFactory
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * Get the cache factory instance.
     *
     * @return \GrahamCampbell\Flysystem\Cache\ConnectionFactory
     */
    public function getCache()
    {
        return $this->cache;
    }
}
