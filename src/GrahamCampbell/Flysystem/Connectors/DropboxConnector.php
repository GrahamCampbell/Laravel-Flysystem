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

use Dropbox\Client;
use Flysystem\Adapter\Dropbox;

/**
 * This is the dropbox connector class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/develop/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class DropboxConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param  array  $config
     * @return \Flysystem\Adapter\Dropbox
     */
    public function connect(array $config)
    {
        $auth = $this->getAuth($config);
        $client = $this->getClient($auth);
        $config = $this->getConfig($config);
        return $this->getAdapter($client, $config);
    }

    protected function getAuth(array $config)
    {
        if (!array_key_exists('token', $config) || !array_key_exists('app', $config)) {
            throw new \InvalidArgumentException('The dropbox connector requires authentication.');
        }

        return array('token' => $config['token'], 'app' => $config['app']);
    }

    protected function getClient(array $auth)
    {
        return new Client($auth['token'], $auth['app']);
    }

    protected function getConfig(array $config)
    {
        $prefix = null;

        if (array_key_exists('prefix', $config)) {
            $prefix = $config['prefix'];
        }

        return array('prefix' => $prefix);
    }

    protected function getAdapter($client, array $config)
    {
        return new Dropobx($client, $config['prefix']);
    }
}
