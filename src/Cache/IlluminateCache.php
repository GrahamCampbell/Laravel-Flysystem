<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Flysystem\Cache;

use Illuminate\Contracts\Cache\Store;
use League\Flysystem\Cached\Storage\AbstractCache;

/**
 * This is the illuminate cache class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class IlluminateCache extends AbstractCache
{
    /**
     * The cache store instance.
     *
     * @var \Illuminate\Contracts\Cache\Store
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
     * Create a new illuminate cache instance.
     *
     * @param \Illuminate\Contracts\Cache\Store $client
     * @param string                            $key
     * @param int                               $ttl
     */
    public function __construct(Store $client, $key = 'flysystem', $ttl = null)
    {
        $this->client = $client;
        $this->key = $key;

        if ($ttl) {
            $this->ttl = (int) ceil($ttl / 60);
        }
    }

    /**
     * Load the cache.
     *
     * @return void
     */
    public function load()
    {
        $contents = $this->client->get($this->key);

        if ($contents !== null) {
            $this->setFromStorage($contents);
        }
    }

    /**
     * Store the cache.
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
     * @return \Illuminate\Contracts\Cache\Store
     */
    public function getClient()
    {
        return $this->client;
    }
}
