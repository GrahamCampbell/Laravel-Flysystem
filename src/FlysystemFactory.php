<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Flysystem;

use GrahamCampbell\Flysystem\Adapters\ConnectionFactory as AdapterFactory;
use GrahamCampbell\Flysystem\Cache\ConnectionFactory as CacheFactory;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\EventableFilesystem\EventableFilesystem;
use League\Flysystem\Filesystem;

/**
 * This is the filesystem factory class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class FlysystemFactory
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

        if (is_array($cache = array_get($config, 'cache', false))) {
            $adapter = new CachedAdapter($adapter, $this->createCache($cache, $manager));
        }

        $options = $this->getOptions($config);

        if (array_get($config, 'eventable', false)) {
            return new EventableFilesystem($adapter, $options);
        }

        return new Filesystem($adapter, $options);
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
        $config = array_except($config, ['cache', 'eventable', 'visibility']);

        return $this->adapter->make($config);
    }

    /**
     * Establish a cache connection.
     *
     * @param array                                      $config
     * @param \GrahamCampbell\Flysystem\FlysystemManager $manager
     *
     * @return \League\Flysystem\Cached\CacheInterface
     */
    public function createCache(array $config, FlysystemManager $manager)
    {
        return $this->cache->make($config, $manager);
    }

    /**
     * Get the flysystem options.
     *
     * @param array $config
     *
     * @return array|null
     */
    protected function getOptions(array $config)
    {
        if ($visibility = array_get($config, 'visibility')) {
            return compact('visibility');
        }
    }

    /**
     * Get the adapter factory instance.
     *
     * @return \GrahamCampbell\Flysystem\Adapters\ConnectionFactory
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
