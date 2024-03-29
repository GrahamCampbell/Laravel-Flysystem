<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Adapter;

use GrahamCampbell\Flysystem\Adapter\ConnectionFactory;
use GrahamCampbell\Flysystem\Adapter\Connector\AwsS3Connector;
use GrahamCampbell\Flysystem\Adapter\Connector\AzureConnector;
use GrahamCampbell\Flysystem\Adapter\Connector\ConnectorInterface;
use GrahamCampbell\Flysystem\Adapter\Connector\DropboxConnector;
use GrahamCampbell\Flysystem\Adapter\Connector\FtpConnector;
use GrahamCampbell\Flysystem\Adapter\Connector\GoogleCloudStorageConnector;
use GrahamCampbell\Flysystem\Adapter\Connector\GridFSConnector;
use GrahamCampbell\Flysystem\Adapter\Connector\LocalConnector;
use GrahamCampbell\Flysystem\Adapter\Connector\NullConnector;
use GrahamCampbell\Flysystem\Adapter\Connector\SftpConnector;
use GrahamCampbell\Flysystem\Adapter\Connector\WebDavConnector;
use GrahamCampbell\Flysystem\Adapter\Connector\ZipConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use InvalidArgumentException;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use Mockery;

/**
 * This is the adapter connection factory test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class ConnectionFactoryTest extends AbstractTestCase
{
    public function testMake()
    {
        $factory = $this->getMockedFactory();

        $return = $factory->make(['driver' => 'local', 'path' => __DIR__, 'name' => 'local']);

        $this->assertInstanceOf(AdapterInterface::class, $return);
    }

    public function createDataProvider()
    {
        return [
            ['awss3', AwsS3Connector::class],
            ['azure', AzureConnector::class],
            ['dropbox', DropboxConnector::class],
            ['ftp', FtpConnector::class],
            ['gcs', GoogleCloudStorageConnector::class],
            ['gridfs', GridFSConnector::class],
            ['local', LocalConnector::class],
            ['null', NullConnector::class],
            ['sftp', SftpConnector::class],
            ['webdav', WebDavConnector::class],
            ['zip', ZipConnector::class],
        ];
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreateWorkingDriver($driver, $class)
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(['driver' => $driver]);

        $this->assertInstanceOf($class, $return);
    }

    public function testCreateEmptyDriverConnector()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A driver must be specified.');

        $factory = $this->getConnectionFactory();

        $factory->createConnector([]);
    }

    public function testCreateUnsupportedDriverConnector()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unsupported driver [unsupported].');

        $factory = $this->getConnectionFactory();

        $factory->createConnector(['driver' => 'unsupported']);
    }

    protected function getConnectionFactory()
    {
        return new ConnectionFactory();
    }

    protected function getMockedFactory()
    {
        $mock = Mockery::mock(ConnectionFactory::class.'[createConnector]');

        $connector = Mockery::mock(ConnectorInterface::class);

        $connector->shouldReceive('connect')->once()
            ->with(['name' => 'local', 'driver' => 'local', 'path' => __DIR__])
            ->andReturn(Mockery::mock(Local::class));

        $mock->shouldReceive('createConnector')->once()
            ->with(['name' => 'local', 'driver' => 'local', 'path' => __DIR__])
            ->andReturn($connector);

        return $mock;
    }
}
