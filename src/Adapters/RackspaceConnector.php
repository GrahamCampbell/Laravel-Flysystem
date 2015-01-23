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

namespace GrahamCampbell\Flysystem\Adapters;

use GrahamCampbell\Manager\ConnectorInterface;
use League\Flysystem\Adapter\Rackspace;
use OpenCloud\ObjectStore\Resource\Container;
use OpenCloud\Rackspace as OpenStackRackspace;

/**
 * This is the rackspace connector class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class RackspaceConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @codeCoverageIgnore
     *
     * @param string[] $config
     *
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
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return string[]
     */
    protected function getAuth(array $config)
    {
        if (!array_key_exists('username', $config) || !array_key_exists('password', $config)) {
            throw new \InvalidArgumentException('The rackspace connector requires authentication.');
        }

        if (!array_key_exists('endpoint', $config) || !array_key_exists('container', $config)) {
            throw new \InvalidArgumentException('The rackspace connector requires configuration.');
        }
        
        if(!array_key_exists('urltype', $config)) {
            $config['urltype'] = null;
        }

        return array_only($config, array('username', 'password', 'endpoint', 'container', 'urltype'));
    }

    /**
     * Get the rackspace client.
     *
     * @param string[] $auth
     *
     * @return \OpenCloud\ObjectStore\Resource\Container
     */
    protected function getClient(array $auth)
    {
        $client = new OpenStackRackspace($auth['endpoint'], array(
            'username' => $auth['username'],
            'apiKey' => $auth['password'],
        ));

        return $client->objectStoreService('cloudFiles', 'LON', $auth['urltype'])->getContainer($auth['container']);
    }

    /**
     * Get the rackspace adapter.
     *
     * @codeCoverageIgnore
     *
     * @param \OpenCloud\ObjectStore\Resource\Container $client
     *
     * @return \League\Flysystem\Adapter\Rackspace
     */
    protected function getAdapter(Container $client)
    {
        return new Rackspace($client);
    }
}
