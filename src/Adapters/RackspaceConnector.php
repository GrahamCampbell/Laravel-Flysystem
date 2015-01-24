<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Flysystem\Adapters;

use GrahamCampbell\Manager\ConnectorInterface;
use InvalidArgumentException;
use League\Flysystem\Rackspace\RackspaceAdapter;
use OpenCloud\ObjectStore\Resource\Container;
use OpenCloud\Rackspace as OpenStackRackspace;

/**
 * This is the rackspace connector class.
 *
 * @author Graham Campbell <graham@mineuk.com>
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
     * @return \League\Flysystem\Rackspace\RackspaceAdapter
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
        if (!array_key_exists('username', $config) || !array_key_exists('apiKey', $config)) {
            throw new InvalidArgumentException('The rackspace connector requires authentication.');
        }

        if (!array_key_exists('endpoint', $config) || !array_key_exists('region', $config)) {
            throw new InvalidArgumentException('The rackspace connector requires both an endpoint an a region.');
        }

        if (!array_key_exists('container', $config)) {
            throw new InvalidArgumentException('The rackspace connector requires a container.');
        }

        return array_only($config, ['username', 'apiKey', 'endpoint', 'region', 'container']);
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
        $client = new OpenStackRackspace($auth['endpoint'], [
            'username' => $auth['username'],
            'apiKey'   => $auth['apiKey'],
        ]);

        return $client->objectStoreService('cloudFiles', $auth['region'])->getContainer($auth['container']);
    }

    /**
     * Get the rackspace adapter.
     *
     * @codeCoverageIgnore
     *
     * @param \OpenCloud\ObjectStore\Resource\Container $client
     *
     * @return \League\Flysystem\Rackspace\RackspaceAdapter
     */
    protected function getAdapter(Container $client)
    {
        return new RackspaceAdapter($client);
    }
}
