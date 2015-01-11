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

use GrahamCampbell\Flysystem\Adapters\CopyConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the copy connector test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class CopyConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getCopyConnector();

        $return = $connector->connect([
            'consumer-key'    => 'your-consumer-key',
            'consumer-secret' => 'your-consumer-secret',
            'access-token'    => 'your-access-token',
            'token-secret'    => 'your-token-secret',
        ]);

        $this->assertInstanceOf('League\Flysystem\Adapter\Copy', $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getCopyConnector();

        $return = $connector->connect([
            'consumer-key'    => 'your-consumer-key',
            'consumer-secret' => 'your-consumer-secret',
            'access-token'    => 'your-access-token',
            'token-secret'    => 'your-token-secret',
            'prefix'          => 'your-prefix',
        ]);

        $this->assertInstanceOf('League\Flysystem\Adapter\Copy', $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutConsumerKey()
    {
        $connector = $this->getCopyConnector();

        $connector->connect([
            'consumer-secret' => 'your-consumer-secret',
            'access-token'    => 'your-access-token',
            'token-secret'    => 'your-token-secret',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutConsumerSecret()
    {
        $connector = $this->getCopyConnector();

        $connector->connect([
            'consumer-key'    => 'your-consumer-key',
            'access-token'    => 'your-access-token',
            'token-secret'    => 'your-token-secret',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutAccessToken()
    {
        $connector = $this->getCopyConnector();

        $connector->connect([
            'consumer-key'    => 'your-consumer-key',
            'consumer-secret' => 'your-consumer-secret',
            'token-secret'    => 'your-token-secret',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutAccessSecret()
    {
        $connector = $this->getCopyConnector();

        $connector->connect([
            'consumer-key'    => 'your-consumer-key',
            'consumer-secret' => 'your-consumer-secret',
            'access-token'    => 'your-access-token',
        ]);
    }

    protected function getCopyConnector()
    {
        return new CopyConnector();
    }
}
