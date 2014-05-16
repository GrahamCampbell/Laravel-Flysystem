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

/**
 * This is the adapter connection factory class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class ConnectionFactory
{
    /**
     * Establish an adapter connection.
     *
     * @param  array   $config
     * @return \League\Flysystem\AdapterInterface
     */
    public function make(array $config)
    {
        return $this->createConnector($config)->connect($config);
    }

    /**
     * Create a connector instance based on the configuration.
     *
     * @param  array  $config
     * @return \GrahamCampbell\Manager\Interfaces\ConnectorInterface
     */
    public function createConnector(array $config)
    {
        if (!isset($config['driver'])) {
            throw new \InvalidArgumentException("A driver must be specified.");
        }

        switch ($config['driver']) {
            case 'awss3':
                return new AwsS3Connector();
            case 'rackspace':
                return new RackspaceConnector();
            case 'dropbox':
                return new DropboxConnector();
            case 'ftp':
                return new FtpConnector();
            case 'local':
                return new LocalConnector();
            case 'sftp':
                return new SftpConnector();
            case 'webdav':
                return new WebDavConnector();
            case 'zip':
                return new ZipConnector();
        }

        throw new \InvalidArgumentException("Unsupported driver [{$config['driver']}]");
    }
}
