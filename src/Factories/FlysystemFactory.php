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

namespace GrahamCampbell\Flysystem\Factories;

use GrahamCampbell\Flysystem\Adapters\ConnectionFactory as AdapterFactory;
use GrahamCampbell\Flysystem\Cache\ConnectionFactory as CacheFactory;
use GrahamCampbell\Flysystem\FlysystemManager;
use League\Flysystem\Filesystem;

/**
 * This is the filesystem factory class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class FlysystemFactory
{
    /**
     * The adapter factory instance.
     *
     * @type \GrahamCampbell\Flysystem\Adapters\ConnectionFactory
     */
    protected $adapter;

    /**
     * The cache factory instance.
     *
     * @type \GrahamCampbell\Flysystem\Cache\ConnectionFactory
     */
    protected $cache;

    /**
     * Create a new filesystem factory instance.
     *
     * @param \GrahamCampbell\Flysystem\Adapters\ConnectionFactory $adapter
     * @param \GrahamCampbell\Flysystem\Cache\ConnectionFactory    $cache
     *
     * @return void
     */
    public function __construct(AdapterFactory $adapter, CacheFactory $cache)
    {
        $this->adapter = $adapter;
        $this->cache = $cache;
    }

    /**
     * Make a new flysystem instance.
     *
     * @param array                                      $config
     * @param \GrahamCampbell\Flysystem\FlysystemManager $manager
     *
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
     * @param array $config
     *
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
     * @param array                                      $config
     * @param \GrahamCampbell\Flysystem\FlysystemManager $manager
     *
     * @return \League\Flysystem\CacheInterface
     */
    public function createCache(array $config, FlysystemManager $manager)
    {
        if (is_array($config = array_get($config, 'cache')) && $config) {
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
