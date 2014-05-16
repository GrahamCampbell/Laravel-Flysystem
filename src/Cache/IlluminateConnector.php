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

namespace GrahamCampbell\Flysystem\Cache;

use Illuminate\Cache\CacheManager;
use Illuminate\Cache\StoreInterface;
use GrahamCampbell\Manager\Interfaces\ConnectorInterface;

/**
 * This is the illuminate connector class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class IlluminateConnector implements ConnectorInterface
{
    /**
     * The cache manager instance.
     *
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * Create a new connection factory instance.
     *
     * @param  \Illuminate\Cache\CacheManager  $cache
     * @return void
     */
    public function __construct(CacheManager $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Establish a cache connection.
     *
     * @param  array  $config
     * @return \GrahamCampbell\Flysystem\Cache\IlluminateCache
     */
    public function connect(array $config)
    {
        $client = $this->getClient($config);
        return $this->getAdapter($client, $config);
    }

    /**
     * Get the cache client.
     *
     * @param  array  $config
     * @return \Illuminate\Cache\StoreInterface
     */
    protected function getClient(array $config)
    {
        $name = array_get($config, 'connector');
        return $this->cache->driver($name)->getStore();
    }

    /**
     * Get the illuminate cache adapter.
     *
     * @param  \Illuminate\Cache\StoreInterface  $client
     * @param  array  $config
     * @return \GrahamCampbell\Flysystem\Cache\IlluminateCache
     */
    protected function getAdapter(StoreInterface $client, array $config)
    {
        $key = array_get($config, 'key', 'flysystem');
        $ttl = array_get($config, 'ttl');

        return new IlluminateCache($client, $key, $ttl);
    }

    /**
     * Get the cache instance.
     *
     * @return \Illuminate\Cache\CacheManager
     */
    public function getCache()
    {
        return $this->cache;
    }
}
