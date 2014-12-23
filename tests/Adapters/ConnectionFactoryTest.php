<?php

/*
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

        $return = $factory->make(array('driver' => 'local', 'path' => __DIR__, 'name' => 'local'));

        $this->assertInstanceOf('League\Flysystem\AdapterInterface', $return);
    }

    public function createDataProvider()
    {
        return array(
            array('awss3', 'AwsS3Connector'),
            array('copy', 'CopyConnector'),
            array('dropbox', 'DropboxConnector'),
            array('ftp', 'FtpConnector'),
            array('local', 'LocalConnector'),
            array('null', 'NullConnector'),
            array('rackspace', 'RackspaceConnector'),
            array('sftp', 'SftpConnector'),
            array('webdav', 'WebDavConnector'),
            array('zip', 'ZipConnector'),
        );
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreateWorkingDriver($driver, $class)
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => $driver));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Adapters\\'.$class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateEmptyDriverConnector()
    {
        $factory = $this->getConnectionFactory();

        $factory->createConnector(array());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreateUnsupportedDriverConnector()
    {
        $factory = $this->getConnectionFactory();

        $factory->createConnector(array('driver' => 'unsupported'));
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
            ->with(array('name' => 'local', 'driver' => 'local', 'path' => __DIR__))
            ->andReturn(Mockery::mock('League\Flysystem\Adapter\Local'));

        $mock->shouldReceive('createConnector')->once()
            ->with(array('name' => 'local', 'driver' => 'local', 'path' => __DIR__))
            ->andReturn($connector);

        return $mock;
    }
}
