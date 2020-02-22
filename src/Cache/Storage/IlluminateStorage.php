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

namespace GrahamCampbell\Flysystem\Cache\Storage;

use Illuminate\Contracts\Cache\Store;
use League\Flysystem\Cached\Storage\AbstractCache;

/**
 * This is the illuminate storage class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class IlluminateStorage extends AbstractCache
{
    /**
     * The cache store instance.
     *
     * @var \Illuminate\Contracts\Cache\Store
     */
    private $store;

    /**
     * The cache key.
     *
     * @var string
     */
    private $key;

    /**
     * The cache ttl in seconds.
     *
     * @var int|null
     */
    private $ttl;

    /**
     * Create a new illuminate storage instance.
     *
     * @param \Illuminate\Contracts\Cache\Store $store
     * @param string                            $key
     * @param int|null                          $ttl
     */
    public function __construct(Store $store, string $key = 'flysystem', int $ttl = null)
    {
        $this->store = $store;
        $this->key = $key;
        $this->ttl = $ttl;
    }

    /**
     * Load the cache.
     *
     * @return void
     */
    public function load()
    {
        $contents = $this->store->get($this->key);

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
            $this->store->put($this->key, $contents, $this->ttl);
        } else {
            $this->store->forever($this->key, $contents);
        }
    }

    /**
     * Get the cache store instance.
     *
     * @return \Illuminate\Contracts\Cache\Store
     */
    public function getStore()
    {
        return $this->store;
    }
}
