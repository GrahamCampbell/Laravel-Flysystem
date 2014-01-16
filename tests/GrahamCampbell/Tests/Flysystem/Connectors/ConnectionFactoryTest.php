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

namespace GrahamCampbell\Tests\Flysystem\Classes;

use Mockery;
use GrahamCampbell\Flysystem\Connectors\ConnectionFactory;
use GrahamCampbell\TestBench\Classes\AbstractTestCase;

/**
 * This is the connection factory test class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class ConnectionFactoryTest extends AbstractTestCase
{
    public function testMake()
    {
        $factory = $this->getMockedFactory();

        $return = $factory->make(array('driver' => 'local', 'path' => __DIR__), 'local');

        $this->assertInstanceOf('League\Flysystem\Filesystem', $return);
    }

    public function testCreateAwsS3Connector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'awss3'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Connectors\AwsS3Connector', $return);
    }

    public function testCreateDropboxConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'dropbox'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Connectors\DropboxConnector', $return);
    }

    public function testCreateFtpConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'ftp'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Connectors\FTPConnector', $return);
    }

    public function testCreateLocalConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'local'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Connectors\LocalConnector', $return);
    }

    public function testCreateSftpConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'sftp'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Connectors\SftpConnector', $return);
    }

    public function testCreateWebDavConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'webdav'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Connectors\WebDavConnector', $return);
    }

    public function testCreateZipConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = $factory->createConnector(array('driver' => 'zip'));

        $this->assertInstanceOf('GrahamCampbell\Flysystem\Connectors\ZipConnector', $return);
    }

    public function testCreateEmptyDriverConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = null;

        try {
            $factory->createConnector(array());
        } catch (\Exception $e) {
            $return = $e;
        }

        $this->assertInstanceOf('InvalidArgumentException', $return);
    }

    public function testCreateUnsupportedDriverConnector()
    {
        $factory = $this->getConnectionFactory();

        $return = null;

        try {
            $factory->createConnector(array('driver' => 'unsupported'));
        } catch (\Exception $e) {
            $return = $e;
        }

        $this->assertInstanceOf('InvalidArgumentException', $return);
    }

    protected function getConnectionFactory()
    {
        return new ConnectionFactory();
    }

    protected function getMockedFactory()
    {
        $mock = Mockery::mock('GrahamCampbell\Flysystem\Connectors\ConnectionFactory[createConnector]');

        $connectory = Mockery::mock('GrahamCampbell\Flysystem\Connectors\LocalConnector');

        $connectory->shouldReceive('connect')->once()
            ->with(array('name' => 'local', 'driver' => 'local', 'path' => __DIR__))
            ->andReturn(Mockery::mock('League\Flysystem\Adapter\Local'));

        $mock->shouldReceive('createConnector')->once()
            ->with(array('name' => 'local', 'driver' => 'local', 'path' => __DIR__))
            ->andReturn($connectory);

        return $mock;
    }
}
