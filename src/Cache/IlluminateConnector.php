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

use GrahamCampbell\Manager\ConnectorInterface;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Contracts\Cache\Store;

/**
 * This is the illuminate connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class IlluminateConnector implements ConnectorInterface
{
    /**
     * The cache factory instance.
     *
     * @var \Illuminate\Contracts\Cache\Factory
     */
    protected $cache;

    /**
     * Create a new illuminate connector instance.
     *
     * @param \Illuminate\Contracts\Cache\Factory $cache
     *
     * @return void
     */
    public function __construct(Factory $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Establish a cache connection.
     *
     * @param string[] $config
     *
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
     * @param string[] $config
     *
     * @return \Illuminate\Contracts\Cache\Store
     */
    protected function getClient(array $config)
    {
        $name = array_get($config, 'connector');

        return $this->cache->driver($name)->getStore();
    }

    /**
     * Get the illuminate cache adapter.
     *
     * @param \Illuminate\Contracts\Cache\Store $client
     * @param string[]                          $config
     *
     * @return \GrahamCampbell\Flysystem\Cache\IlluminateCache
     */
    protected function getAdapter(Store $client, array $config)
    {
        $key = array_get($config, 'key', 'flysystem');
        $ttl = array_get($config, 'ttl');

        return new IlluminateCache($client, $key, $ttl);
    }

    /**
     * Get the cache instance.
     *
     * @return \Illuminate\Contracts\Cache\Factory
     */
    public function getCache()
    {
        return $this->cache;
    }
}
