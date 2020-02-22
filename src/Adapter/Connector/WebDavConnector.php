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
use League\Flysystem\WebDAV\WebDAVAdapter;
use Sabre\DAV\Client;

/**
 * This is the webdav connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
final class WebDavConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \League\Flysystem\WebDAV\WebDAVAdapter
     */
    public function connect(array $config)
    {
        $client = self::getClient($config);
        $config = self::getConfig($config);

        return self::getAdapter($client, $config);
    }

    /**
     * Get the webdav client.
     *
     * @param string[] $config
     *
     * @return \Sabre\DAV\Client
     */
    private static function getClient(array $config)
    {
        return new Client($config);
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
     * Get the webdav adapter.
     *
     * @param \Sabre\DAV\Client $client
     * @param string[]          $config
     *
     * @return \League\Flysystem\WebDAV\WebDAVAdapter
     */
    private static function getAdapter(Client $client, array $config)
    {
        return new WebDAVAdapter($client, $config['prefix']);
    }
}
