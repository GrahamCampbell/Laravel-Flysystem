<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Adapters;

use GrahamCampbell\Flysystem\Adapters\ConnectionFactory;
use GrahamCampbell\TestBench\AbstractTestCase;
use Mockery;

/**
 * This is the adapter connection factory test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class ConnectionFactoryTest extends AbstractTestCase
{
    public function testMake()
    {
        $factory = $this->getMockedFactory();

        $return = $factory->make(['driver' => 'local', 'path' => __DIR__, 'name' => 'local']);

        $this->assertInstanceOf('League\Flysystem\AdapterInterface', $return);
    }

    public function createDataProvider()
    {
        return [
            ['awss3', 'AwsS3Connector'],
            ['azure', 'AzureConnector'],
            ['copy', 'CopyConnector'],
            ['dropbox', 'DropboxConnector'],
            ['ftp', 'FtpConnector'],
            ['gridfs', 'GridFSConnector'],
            ['local', 'LocalConnector'],
            ['null', 'NullConnector'],
            ['rackspace', 'RackspaceConnector'],
            ['sftp', 'SftpConnector'],
            ['webdav', 'WebDavConnector'],
            ['zip', 'ZipConnector'],
        ];
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreateWorkingDriver($driver, $class)
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(['driver' => $driver]);

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Adapters\\'.$class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateEmptyDriverConnector()
    {
        $factory = $this->getConnectionFactory();

        $factory->createConnector([]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateUnsupportedDriverConnector()
    {
        $factory = $this->getConnectionFactory();

        $factory->createConnector(['driver' => 'unsupported']);
    }

    protected function getConnectionFactory()
    {
        return new ConnectionFactory();
    }

    protected function getMockedFactory()
    {
        $mock = Mockery::mock('GrahamCampbell\Flysystem\Adapters\ConnectionFactory[createConnector]');

        $connector = Mockery::mock('GrahamCampbell\Flysystem\Adapters\LocalConnector');

        $connector->shouldReceive('connect')->once()
            ->with(['name' => 'local', 'driver' => 'local', 'path' => __DIR__])
            ->andReturn(Mockery::mock('League\Flysystem\Adapter\Local'));

        $mock->shouldReceive('createConnector')->once()
            ->with(['name' => 'local', 'driver' => 'local', 'path' => __DIR__])
            ->andReturn($connector);

        return $mock;
    }
}
