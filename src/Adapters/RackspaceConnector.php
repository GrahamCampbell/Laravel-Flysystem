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

namespace GrahamCampbell\Flysystem\Adapters;

use OpenCloud\OpenStack;
use League\Flysystem\Adapter\Rackspace;
use OpenCloud\ObjectStore\Resource\Container;
use GrahamCampbell\Manager\ConnectorInterface;

/**
 * This is the rackspace connector class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class RackspaceConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param  array  $config
     * @return \League\Flysystem\Adapter\Rackspace
     */
    public function connect(array $config)
    {
        $auth = $this->getAuth($config);
        $client = $this->getClient($auth);
        return $this->getAdapter($client);
    }

    /**
     * Get the authentication data.
     *
     * @param  array  $config
     * @return array
     */
    protected function getAuth(array $config)
    {
        if (!array_key_exists('username', $config) || !array_key_exists('password', $config)) {
            throw new \InvalidArgumentException('The rackspace connector requires authentication.');
        }

        if (!array_key_exists('endpoint', $config) || !array_key_exists('container', $config)) {
            throw new \InvalidArgumentException('The rackspace connector requires configuration.');
        }

        return $config;
    }

    /**
     * Get the rackspace client.
     *
     * @param  array  $auth
     * @return \OpenCloud\ObjectStore\Resource\Container
     */
    protected function getClient(array $auth)
    {
        $client = new OpenStack($auth['endpoint'], array(
            'username' => $auth['username'],
            'password' => $auth['password']
        ));

        return $client->objectStoreService('cloudFiles', 'LON')->getContainer($auth['container']);
    }

    /**
     * Get the rackspace adapter.
     *
     * @param  \OpenCloud\ObjectStore\Resource\Container  $client
     * @param  array  $config
     * @return \League\Flysystem\Adapter\Rackspace
     */
    protected function getAdapter(Container $client, array $config)
    {
        return new Rackspace($client);
    }
}
