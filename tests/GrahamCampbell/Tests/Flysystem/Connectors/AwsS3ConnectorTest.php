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
use GrahamCampbell\Flysystem\Connectors\AwsS3Connector;
use GrahamCampbell\TestBench\Classes\AbstractTestCase;

/**
 * This is the awss3 connector test class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/develop/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class AwsS3ConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect(array(
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket'
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
            'prefix' => 'your-prefix'
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
            'region' => 'eu-west-1'
        ));

        $this->assertInstanceOf('League\Flysystem\Adapter\AwsS3', $return);
    }

    public function testConnectWithoutBucket()
    {
        $connector = $this->getAwsS3Connector();

        $return = null;

        try {
            $connector->connect(array('key' => 'your-key', 'secret' => 'your-secret'));
        } catch (\Exception $e) {
            $return = $e;
        }

        $this->assertInstanceOf('InvalidArgumentException', $return);
    }

    public function testConnectWithoutKey()
    {
        $connector = $this->getAwsS3Connector();

        $return = null;

        try {
            $connector->connect(array('secret' => 'your-secret', 'bucket' => 'your-bucket'));
        } catch (\Exception $e) {
            $return = $e;
        }

        $this->assertInstanceOf('InvalidArgumentException', $return);
    }

    public function testConnectWithoutSecret()
    {
        $connector = $this->getAwsS3Connector();

        $return = null;

        try {
            $connector->connect(array('key' => 'your-key', 'bucket' => 'your-bucket'));
        } catch (\Exception $e) {
            $return = $e;
        }

        $this->assertInstanceOf('InvalidArgumentException', $return);
    }

    protected function getAwsS3Connector()
    {
        return new AwsS3Connector();
    }
}
