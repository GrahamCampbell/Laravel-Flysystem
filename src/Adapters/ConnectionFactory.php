<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Flysystem\Adapters;

use InvalidArgumentException;

/**
 * This is the adapter connection factory class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ConnectionFactory
{
    /**
     * Establish an adapter connection.
     *
     * @param array $config
     *
     * @return \League\Flysystem\AdapterInterface
     */
    public function make(array $config)
    {
        return $this->createConnector($config)->connect($config);
    }

    /**
     * Create a connector instance based on the configuration.
     *
     * @param array $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \GrahamCampbell\Manager\ConnectorInterface
     */
    public function createConnector(array $config)
    {
        if (!isset($config['driver'])) {
            throw new InvalidArgumentException('A driver must be specified.');
        }

        switch ($config['driver']) {
            case 'awss3':
                return new AwsS3Connector();
            case 'azure':
                return new AzureConnector();
            case 'copy':
                return new CopyConnector();
            case 'dropbox':
                return new DropboxConnector();
            case 'ftp':
                return new FtpConnector();
            case 'gridfs':
                return new GridFSConnector();
            case 'local':
                return new LocalConnector();
            case 'null':
                return new NullConnector();
            case 'rackspace':
                return new RackspaceConnector();
            case 'sftp':
                return new SftpConnector();
            case 'webdav':
                return new WebDavConnector();
            case 'zip':
                return new ZipConnector();
        }

        throw new InvalidArgumentException("Unsupported driver [{$config['driver']}].");
    }
}
