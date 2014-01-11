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

namespace GrahamCampbell\Flysystem\Connectors;

use Sabre\DAV\Client;
use Flysystem\Adapters\WebDav;

/**
 * This is the webdav connector class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/develop/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class WebDavConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param  array  $config
     * @return \Flysystem\Adapter\WebDav
     */
    public function connect(array $config)
    {
        $client = $this->getClient($config);
        return $this->getAdapter($client);
    }

    protected function getClient(array $config)
    {
        return new Client($config);
    }

    protected function getAdapter($client)
    {
        return new WebDav($client);
    }
}
