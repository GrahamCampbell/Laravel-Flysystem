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

namespace GrahamCampbell\Flysystem\Cache;

use Illuminate\Cache\StoreInterface;
use League\Flysystem\Cache\AbstractCache;

/**
 * This is the illuminate cache class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class IlluminateCache extends AbstractCache
{
    /**
     * The cache store instance.
     *
     * @var \Illuminate\Cache\StoreInterface
     */
    protected $client;

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
     * @param \Illuminate\Cache\StoreInterface $client
     * @param string                           $key
     * @param int                              $ttl
     */
    public function __construct(StoreInterface $client, $key = 'flysystem', $ttl = null)
    {
        $this->client = $client;
        $this->key = $key;
        if ($ttl) {
            $this->ttl = (int) ceil($ttl / 60);
        }
    }

    /**
     * Load the cache
     *
     * @return void
     */
    public function load()
    {
        if (($contents = $this->client->get($this->key)) !== null) {
            $this->setFromStorage($contents);
        }
    }

    /**
     * Store the cache
     *
     * @return void
     */
    public function save()
    {
        $contents = $this->getForStorage();

        if ($this->ttl !== null) {
            $this->client->put($this->key, $contents, $this->ttl);
        } else {
            $this->client->forever($this->key, $contents);
        }
    }

    /**
     * Get the cache store instance.
     *
     * @return \Illuminate\Cache\StoreInterface
     */
    public function getClient()
    {
        return $this->client;
    }
}
