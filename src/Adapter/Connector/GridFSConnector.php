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
use League\Flysystem\GridFS\GridFSAdapter;
use MongoClient;

/**
 * This is the gridfs connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
final class GridFSConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \League\Flysystem\GridFS\GridFSAdapter
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
        if (!array_key_exists('server', $config)) {
            throw new InvalidArgumentException('The gridfs connector requires server configuration.');
        }

        return Arr::only($config, ['server']);
    }

    /**
     * Get the gridfs client.
     *
     * @param string[] $auth
     *
     * @return \MongoClient
     */
    private static function getClient(array $auth)
    {
        return new MongoClient($auth['server']);
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
        if (!array_key_exists('database', $config)) {
            throw new InvalidArgumentException('The gridfs connector requires database configuration.');
        }

        return Arr::only($config, ['database']);
    }

    /**
     * Get the gridfs adapter.
     *
     * @param \MongoClient $client
     * @param string[]     $config
     *
     * @return \League\Flysystem\GridFS\GridFSAdapter
     */
    private static function getAdapter(MongoClient $client, array $config)
    {
        $fs = $client->selectDB($config['database'])->getGridFS();

        return new GridFSAdapter($fs);
    }
}
