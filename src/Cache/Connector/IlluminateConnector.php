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

namespace GrahamCampbell\Flysystem\Cache\Connector;

use GrahamCampbell\Flysystem\Cache\Storage\IlluminateStorage;
use GrahamCampbell\Manager\ConnectorInterface;
use Illuminate\Contracts\Cache\Factory;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Arr;
use InvalidArgumentException;

/**
 * This is the illuminate connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
final class IlluminateConnector implements ConnectorInterface
{
    /**
     * The cache factory instance.
     *
     * @var \Illuminate\Contracts\Cache\Factory|null
     */
    private $cache;

    /**
     * Create a new illuminate connector instance.
     *
     * @param \Illuminate\Contracts\Cache\Factory|null $cache
     *
     * @return void
     */
    public function __construct(Factory $cache = null)
    {
        $this->cache = $cache;
    }

    /**
     * Establish a cache connection.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \GrahamCampbell\Flysystem\Cache\Storage\IlluminateStorage
     */
    public function connect(array $config)
    {
        $store = $this->getStore($config);

        return self::getAdapter($store, $config);
    }

    /**
     * Get the cache store.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \Illuminate\Contracts\Cache\Store
     */
    private function getStore(array $config)
    {
        if (!$this->cache) {
            throw new InvalidArgumentException('Illuminate caching support not available.');
        }

        $name = Arr::get($config, 'connector');

        return $this->cache->store($name)->getStore();
    }

    /**
     * Get the illuminate cache adapter.
     *
     * @param \Illuminate\Contracts\Cache\Store $store
     * @param string[]                          $config
     *
     * @return \GrahamCampbell\Flysystem\Cache\Storage\IlluminateStorage
     */
    private static function getAdapter(Store $store, array $config)
    {
        $key = Arr::get($config, 'key', 'flysystem');
        $ttl = Arr::get($config, 'ttl');

        return new IlluminateStorage($store, $key, $ttl);
    }

    /**
     * Get the cache instance.
     *
     * @return \Illuminate\Contracts\Cache\Factory|null
     */
    public function getCache()
    {
        return $this->cache;
    }
}
