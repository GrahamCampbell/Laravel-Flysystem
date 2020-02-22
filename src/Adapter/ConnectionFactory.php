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

namespace GrahamCampbell\Flysystem\Adapter;

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
     * @throws \InvalidArgumentException
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
                return new Connector\AwsS3Connector();
            case 'azure':
                return new Connector\AzureConnector();
            case 'dropbox':
                return new Connector\DropboxConnector();
            case 'ftp':
                return new Connector\FtpConnector();
            case 'gcs':
                return new Connector\GoogleCloudStorageConnector();
            case 'gridfs':
                return new Connector\GridFSConnector();
            case 'local':
                return new Connector\LocalConnector();
            case 'null':
                return new Connector\NullConnector();
            case 'sftp':
                return new Connector\SftpConnector();
            case 'webdav':
                return new Connector\WebDavConnector();
            case 'zip':
                return new Connector\ZipConnector();
        }

        throw new InvalidArgumentException("Unsupported driver [{$config['driver']}].");
    }
}
