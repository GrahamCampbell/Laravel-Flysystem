<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Flysystem\Adapters;

use Barracuda\Copy\API;
use GrahamCampbell\Manager\ConnectorInterface;
use InvalidArgumentException;
use League\Flysystem\Copy\CopyAdapter;

/**
 * This is the copy connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class CopyConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\Copy\CopyAdapter
     */
    public function connect(array $config)
    {
        $auth = $this->getAuth($config);
        $client = $this->getClient($auth);
        $config = $this->getConfig($config);

        return $this->getAdapter($client, $config);
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
        if (!array_key_exists('consumer-key', $config) || !array_key_exists('consumer-secret', $config)) {
            throw new InvalidArgumentException('The copy connector requires consumer configuration.');
        }

        if (!array_key_exists('access-token', $config) || !array_key_exists('token-secret', $config)) {
            throw new InvalidArgumentException('The copy connector requires authentication.');
        }

        return array_only($config, ['consumer-key', 'consumer-secret', 'access-token', 'token-secret']);
    }

    /**
     * Get the copy client.
     *
     * @param string[] $auth
     *
     * @return \Barracuda\Copy\API
     */
    protected function getClient(array $auth)
    {
        return new API($auth['consumer-key'], $auth['consumer-secret'], $auth['access-token'], $auth['token-secret']);
    }

    /**
     * Get the configuration.
     *
     * @param string[] $config
     *
     * @return string[]
     */
    protected function getConfig(array $config)
    {
        if (!array_key_exists('prefix', $config)) {
            $config['prefix'] = null;
        }

        return array_only($config, ['prefix']);
    }

    /**
     * Get the copy adapter.
     *
     * @param \Barracuda\Copy\API $client
     * @param string[]            $config
     *
     * @return \League\Flysystem\Copy\CopyAdapter
     */
    protected function getAdapter(API $client, array $config)
    {
        return new CopyAdapter($client, $config['prefix']);
    }
}
