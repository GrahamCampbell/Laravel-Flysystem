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
use League\Flysystem\WebDAV\WebDAVAdapter;
use Sabre\DAV\Client;

/**
 * This is the webdav connector class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class WebDavConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\WebDAV\WebDAVAdapter
     */
    public function connect(array $config)
    {
        $client = $this->getClient($config);

        return $this->getAdapter($client);
    }

    /**
     * Get the webdav client.
     *
     * @param string[] $config
     *
     * @return \Sabre\DAV\Client
     */
    protected function getClient(array $config)
    {
        return new Client($config);
    }

    /**
     * Get the webdav adapter.
     *
     * @param \Sabre\DAV\Client $client
     *
     * @return \League\Flysystem\WebDAV\WebDAVAdapter
     */
    protected function getAdapter(Client $client)
    {
        return new WebDAVAdapter($client);
    }
}
