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

namespace GrahamCampbell\Tests\Flysystem\Adapters;

use GrahamCampbell\Flysystem\Adapters\ConnectionFactory;
use GrahamCampbell\TestBench\AbstractTestCase;
use Mockery;

/**
 * This is the adapter connection factory test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
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
            ['copy', 'CopyConnector'],
            ['dropbox', 'DropboxConnector'],
            ['ftp', 'FtpConnector'],
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
