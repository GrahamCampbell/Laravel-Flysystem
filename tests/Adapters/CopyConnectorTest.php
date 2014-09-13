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

use GrahamCampbell\Flysystem\Adapters\CopyConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the copy connector test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class CopyConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getCopyConnector();

        $return = $connector->connect(array(
            'consumer-key'    => 'your-consumer-key',
            'consumer-secret' => 'your-consumer-secret',
            'access-token'    => 'your-access-token',
            'token-secret'    => 'your-token-secret'
        ));

        $this->assertInstanceOf('League\Flysystem\Adapter\Copy', $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getCopyConnector();

        $return = $connector->connect(array(
            'consumer-key'    => 'your-consumer-key',
            'consumer-secret' => 'your-consumer-secret',
            'access-token'    => 'your-access-token',
            'token-secret'    => 'your-token-secret',
            'prefix'          => 'your-prefix'
        ));

        $this->assertInstanceOf('League\Flysystem\Adapter\Copy', $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutConsumerKey()
    {
        $connector = $this->getCopyConnector();

        $connector->connect(array(
            'consumer-secret' => 'your-consumer-secret',
            'access-token'    => 'your-access-token',
            'token-secret'    => 'your-token-secret'
        ));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutConsumerSecret()
    {
        $connector = $this->getCopyConnector();

        $connector->connect(array(
            'consumer-key'    => 'your-consumer-key',
            'access-token'    => 'your-access-token',
            'token-secret'    => 'your-token-secret'
        ));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutAccessToken()
    {
        $connector = $this->getCopyConnector();

        $connector->connect(array(
            'consumer-key'    => 'your-consumer-key',
            'consumer-secret' => 'your-consumer-secret',
            'token-secret'    => 'your-token-secret'
        ));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutAccessSecret()
    {
        $connector = $this->getCopyConnector();

        $connector->connect(array(
            'consumer-key'    => 'your-consumer-key',
            'consumer-secret' => 'your-consumer-secret',
            'access-token'    => 'your-access-token'
        ));
    }

    protected function getCopyConnector()
    {
        return new CopyConnector();
    }
}
