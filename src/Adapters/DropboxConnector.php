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

use Dropbox\Client;
use GrahamCampbell\Manager\ConnectorInterface;
use InvalidArgumentException;
use League\Flysystem\Dropbox\DropboxAdapter;

/**
 * This is the dropbox connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DropboxConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\Dropbox\DropboxAdapter
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
        if (!array_key_exists('token', $config) || !array_key_exists('app', $config)) {
            throw new InvalidArgumentException('The dropbox connector requires authentication.');
        }

        return array_only($config, ['token', 'app']);
    }

    /**
     * Get the dropbox client.
     *
     * @param string[] $auth
     *
     * @return \Dropbox\Client
     */
    protected function getClient(array $auth)
    {
        return new Client($auth['token'], $auth['app']);
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
     * Get the dropbox adapter.
     *
     * @param \Dropbox\Client $client
     * @param string[]        $config
     *
     * @return \League\Flysystem\Dropbox\DropboxAdapter
     */
    protected function getAdapter(Client $client, array $config)
    {
        return new DropboxAdapter($client, $config['prefix']);
    }
}
