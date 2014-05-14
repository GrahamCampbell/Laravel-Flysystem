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

use Sabre\DAV\Client;
use League\Flysystem\Adapter\WebDav;

/**
 * This is the webdav connector class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class WebDavConnector
{
    /**
     * Establish an adapter connection.
     *
     * @param  array  $config
     * @return \League\Flysystem\Adapter\WebDav
     */
    public function connect(array $config)
    {
        $client = $this->getClient($config);
        return $this->getAdapter($client);
    }

    /**
     * Get the webdav client.
     *
     * @param  array  $config
     * @return \Sabre\DAV\Client
     */
    protected function getClient(array $config)
    {
        return new Client($config);
    }

    /**
     * Get the webdav adapter.
     *
     * @param  \Sabre\DAV\Client  $client
     * @return \League\Flysystem\Adapter\WebDav
     */
    protected function getAdapter(Client $client)
    {
        return new WebDav($client);
    }
}
