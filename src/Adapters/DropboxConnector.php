<?php

/**
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

use Dropbox\Client;
use GrahamCampbell\Manager\ConnectorInterface;
use League\Flysystem\Adapter\Dropbox;

/**
 * This is the dropbox connector class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class DropboxConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\Adapter\Dropbox
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
            throw new \InvalidArgumentException('The dropbox connector requires authentication.');
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
     * @return \League\Flysystem\Adapter\Dropbox
     */
    protected function getAdapter(Client $client, array $config)
    {
        return new Dropbox($client, $config['prefix']);
    }
}
