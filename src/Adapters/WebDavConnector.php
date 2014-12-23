<?php

/*
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
use League\Flysystem\WebDAV\Adapter;
use Sabre\DAV\Client;

/**
 * This is the webdav connector class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class WebDavConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\WebDAV\Adapter
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
     * @return \League\Flysystem\WebDAV\Adapter
     */
    protected function getAdapter(Client $client)
    {
        return new Adapter($client);
    }
}
