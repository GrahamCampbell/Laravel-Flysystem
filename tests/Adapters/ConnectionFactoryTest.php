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

use Mockery;
use GrahamCampbell\Flysystem\Adapters\ConnectionFactory;
use GrahamCampbell\TestBench\AbstractTestCase;

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

    public function testCreateAwsS3Connector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'awss3'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Adapters\AwsS3Connector', $return);
    }

    public function testCreateDropboxConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'dropbox'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Adapters\DropboxConnector', $return);
    }

    public function testCreateFtpConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'ftp'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Adapters\FTPConnector', $return);
    }

    public function testCreateLocalConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'local'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Adapters\LocalConnector', $return);
    }

    public function testCreateNullConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'null'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Adapters\NullConnector', $return);
    }

    public function testCreateRackspaceConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'rackspace'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Adapters\RackspaceConnector', $return);
    }

    public function testCreateSftpConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'sftp'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Adapters\SftpConnector', $return);
    }

    public function testCreateWebDavConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'webdav'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Adapters\WebDavConnector', $return);
    }

    public function testCreateZipConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'zip'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Adapters\ZipConnector', $return);
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
