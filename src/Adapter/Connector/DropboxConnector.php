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

namespace GrahamCampbell\Flysystem\Adapter\Connector;

use GrahamCampbell\Manager\ConnectorInterface;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use Spatie\Dropbox\Client;
use Spatie\FlysystemDropbox\DropboxAdapter;

/**
 * This is the dropbox connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
final class DropboxConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \Spatie\FlysystemDropbox\DropboxAdapter
     */
    public function connect(array $config)
    {
        $auth = self::getAuth($config);
        $client = self::getClient($auth);
        $config = self::getConfig($config);

        return self::getAdapter($client, $config);
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
    private static function getAuth(array $config)
    {
        if (!array_key_exists('token', $config)) {
            throw new InvalidArgumentException('The dropbox connector requires authentication.');
        }

        return Arr::only($config, ['token']);
    }

    /**
     * Get the dropbox client.
     *
     * @param string[] $auth
     *
     * @return \Spatie\Dropbox\Client
     */
    private static function getClient(array $auth)
    {
        return new Client($auth['token']);
    }

    /**
     * Get the configuration.
     *
     * @param string[] $config
     *
     * @return string[]
     */
    private static function getConfig(array $config)
    {
        if (!array_key_exists('prefix', $config)) {
            $config['prefix'] = null;
        }

        return Arr::only($config, ['prefix']);
    }

    /**
     * Get the dropbox adapter.
     *
     * @param \Spatie\Dropbox\Client $client
     * @param string[]               $config
     *
     * @return \Spatie\FlysystemDropbox\DropboxAdapter
     */
    private static function getAdapter(Client $client, array $config)
    {
        return new DropboxAdapter($client, (string) $config['prefix']);
    }
}
