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

use GrahamCampbell\Flysystem\Adapters\AwsS3Connector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the awss3 connector test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class AwsS3ConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect(array(
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
        ));

        $this->assertInstanceOf('League\Flysystem\Adapter\AwsS3', $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect(array(
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
            'prefix' => 'your-prefix',
        ));

        $this->assertInstanceOf('League\Flysystem\Adapter\AwsS3', $return);
    }

    public function testConnectWithRegion()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect(array(
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
            'region' => 'eu-west-1',
        ));

        $this->assertInstanceOf('League\Flysystem\Adapter\AwsS3', $return);
    }

    public function testConnectWithBaseUrl()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect(array(
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'base_url' => 'your-url',
        ));

        $this->assertInstanceOf('League\Flysystem\Adapter\AwsS3', $return);
    }

    public function testConnectWithOptions()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect(array(
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'options'  => array('foo' => 'bar'),
        ));

        $this->assertInstanceOf('League\Flysystem\Adapter\AwsS3', $return);
    }

    public function testConnectWithEverything()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect(array(
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'region'   => 'eu-west-1',
            'base_url' => 'your-url',
            'options'  => array('foo' => 'bar'),
        ));

        $this->assertInstanceOf('League\Flysystem\Adapter\AwsS3', $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutBucket()
    {
        $connector = $this->getAwsS3Connector();

        $connector->connect(array('key' => 'your-key', 'secret' => 'your-secret'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutKey()
    {
        $connector = $this->getAwsS3Connector();

        $connector->connect(array('secret' => 'your-secret', 'bucket' => 'your-bucket'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutSecret()
    {
        $connector = $this->getAwsS3Connector();

        $connector->connect(array('key' => 'your-key', 'bucket' => 'your-bucket'));
    }

    protected function getAwsS3Connector()
    {
        return new AwsS3Connector();
    }
}
