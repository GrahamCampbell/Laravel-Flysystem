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

use Illuminate\Cache\StoreInterface;
use League\Flysystem\Cache\AbstractCache;

/**
 * This is the illuminate cache class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class IlluminateCache extends AbstractCache
{
    /**
     * The cache store instance.
     *
     * @var \Illuminate\Cache\StoreInterface
     */
    protected $cache;

    /**
     * The cache key.
     *
     * @var string
     */
    protected $key;

    /**
     * The cache ttl in mins.
     *
     * @var int
     */
    protected $ttl;

    /**
     * Constructor
     *
     * @param  \Illuminate\Cache\StoreInterface  $cache
     * @param  string  $key
     * @param  int     $ttl
     */
    public function __construct(StoreInterface $cache, $key = 'flysystem', $ttl = null)
    {
        $this->cache = $cache;
        $this->key = $key;
        $this->ttl = ceil($ttl / 60);
    }

    /**
     * Load the cache
     *
     * @return null
     */
    public function load()
    {
        if (($contents = $this->cache->get($this->key)) !== null) {
            $this->setFromStorage($contents);
        }
    }

    /**
     * Store the cache
     *
     * @return null
     */
    public function save()
    {
        $contents = $this->getForStorage();

        if ($this->ttl !== null) {
            $this->cache->put($this->key, $contents, $this->ttl);
        } else {
            $this->cache->forever($this->key, $contents);
        }
    }

    /**
     * Get the cache store instance.
     *
     * @return \Illuminate\Cache\CacheStore
     */
    public function getCache()
    {
        return $this->cache;
    }
}
