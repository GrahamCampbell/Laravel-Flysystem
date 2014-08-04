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

use GrahamCampbell\Flysystem\FlysystemManager;
use GrahamCampbell\Manager\ConnectorInterface;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Cache\Adapter;

/**
 * This is the adapter connector class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class AdapterConnector implements ConnectorInterface
{
    /**
     * The flysysten manager instance.
     *
     * @var \GrahamCampbell\Flysystem\FlysystemManager
     */
    protected $manager;

    /**
     * Create a new connection factory instance.
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
     * @return \League\Flysystem\Cache\Adapter
     */
    public function connect(array $config)
    {
        $config = $this->getConfig($config);
        $client = $this->getClient($config);
        return $this->getAdapter($client, $config);
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
    protected function getConfig(array $config)
    {
        if (!array_key_exists('adapter', $config)) {
            throw new \InvalidArgumentException('The adapter connector requires an adapter.');
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
    protected function getClient(array $config)
    {
        $name = array_get($config, 'adapter');
        $config = $this->manager->getConnectionConfig($name);
        return $this->manager->getFactory()->createAdapter($config);
    }

    /**
     * Get the adapter cache adapter.
     *
     * @param \League\Flysystem\AdapterInterface $client
     * @param string[]                           $config
     *
     * @return \League\Flysystem\Cache\Adapter
     */
    protected function getAdapter(AdapterInterface $client, array $config)
    {
        $file = array_get($config, 'file', 'flysystem.json');
        $ttl = array_get($config, 'ttl');

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
