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

use GrahamCampbell\Flysystem\FlysystemManager;
use GrahamCampbell\Manager\ConnectorInterface;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Cached\Storage\Adapter;

/**
 * This is the adapter connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
final class AdapterConnector implements ConnectorInterface
{
    /**
     * The flysysten manager instance.
     *
     * @var \GrahamCampbell\Flysystem\FlysystemManager
     */
    private $manager;

    /**
     * Create a new adapter connector instance.
     *
     * @param \GrahamCampbell\Flysystem\FlysystemManager $manager
     *
     * @return void
     */
    public function __construct(FlysystemManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Establish a cache connection.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \League\Flysystem\Cached\Storage\Adapter
     */
    public function connect(array $config)
    {
        $config = self::getConfig($config);
        $client = $this->getClient($config);

        return self::getAdapter($client, $config);
    }

    /**
     * Get the configuration.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return string[]
     */
    private static function getConfig(array $config)
    {
        if (!array_key_exists('adapter', $config)) {
            throw new InvalidArgumentException('The adapter connector requires adapter configuration.');
        }

        return $config;
    }

    /**
     * Get the cache client.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\AdapterInterface
     */
    private function getClient(array $config)
    {
        $name = Arr::get($config, 'adapter');
        $config = $this->manager->getConnectionConfig($name);

        return $this->manager->getFactory()->createAdapter($config);
    }

    /**
     * Get the adapter cache adapter.
     *
     * @param \League\Flysystem\AdapterInterface $client
     * @param string[]                           $config
     *
     * @return \League\Flysystem\Cached\Storage\Adapter
     */
    private static function getAdapter(AdapterInterface $client, array $config)
    {
        $file = Arr::get($config, 'file', 'flysystem.json');
        $ttl = Arr::get($config, 'ttl');

        return new Adapter($client, $file, $ttl);
    }

    /**
     * Get the flysystem manager instance.
     *
     * @return \GrahamCampbell\Flysystem\FlysystemManager
     */
    public function getManager()
    {
        return $this->manager;
    }
}
