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

namespace GrahamCampbell\Flysystem\Cache;

use GrahamCampbell\Flysystem\FlysystemManager;
use Illuminate\Contracts\Cache\Factory;
use InvalidArgumentException;

/**
 * This is the cache connection factory class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ConnectionFactory
{
    /**
     * The cache factory instance.
     *
     * @var \Illuminate\Contracts\Cache\Factory
     */
    protected $cache;

    /**
     * Create a new connection factory instance.
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
     * @param array                                      $config
     * @param \GrahamCampbell\Flysystem\FlysystemManager $manager
     *
     * @return \League\Flysystem\Cached\CacheInterface
     */
    public function make(array $config, FlysystemManager $manager)
    {
        return $this->createConnector($config, $manager)->connect($config);
    }

    /**
     * Create a connector instance based on the configuration.
     *
     * @param array                                      $config
     * @param \GrahamCampbell\Flysystem\FlysystemManager $manager
     *
     * @throws \InvalidArgumentException
     *
     * @return \GrahamCampbell\Manager\ConnectorInterface
     */
    public function createConnector(array $config, FlysystemManager $manager)
    {
        if (!isset($config['driver'])) {
            throw new InvalidArgumentException('A driver must be specified.');
        }

        switch ($config['driver']) {
            case 'illuminate':
                return new IlluminateConnector($this->cache);
            case 'adapter':
                return new AdapterConnector($manager);
        }

        throw new InvalidArgumentException("Unsupported driver [{$config['driver']}].");
    }

    /**
     * Get the cache factory instance.
     *
     * @codeCoverageIgnore
     *
     * @return \Illuminate\Contracts\Cache\Factory
     */
    public function getCache()
    {
        return $this->cache;
    }
}
