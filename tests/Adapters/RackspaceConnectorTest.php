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

use GrahamCampbell\Flysystem\Adapters\RackspaceConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the rackspace connector test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class RackspaceConnectorTest extends AbstractTestCase
{
    /**
     * @expectedException \Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function testConnect()
    {
        $connector = $this->getRackspaceConnector();

        $connector->connect([
            'endpoint'  => 'https://lon.identity.api.rackspacecloud.com/v2.0/',
            'region'    => 'LON',
            'username'  => 'your-username',
            'apiKey'    => 'your-api-key',
            'container' => 'your-container',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutEndpoint()
    {
        $connector = $this->getRackspaceConnector();

        $connector->connect([
            'region'    => 'LON',
            'username'  => 'your-username',
            'apiKey'    => 'your-api-key',
            'container' => 'your-container',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutRegion()
    {
        $connector = $this->getRackspaceConnector();

        $connector->connect([
            'endpoint'  => 'https://lon.identity.api.rackspacecloud.com/v2.0/',
            'username'  => 'your-username',
            'apiKey'    => 'your-api-key',
            'container' => 'your-container',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutUsername()
    {
        $connector = $this->getRackspaceConnector();

        $connector->connect([
            'endpoint'  => 'https://lon.identity.api.rackspacecloud.com/v2.0/',
            'region'    => 'LON',
            'apiKey'    => 'your-api-key',
            'container' => 'your-container',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutApiKey()
    {
        $connector = $this->getRackspaceConnector();

        $connector->connect([
            'endpoint'  => 'https://lon.identity.api.rackspacecloud.com/v2.0/',
            'region'    => 'LON',
            'username'  => 'your-username',
            'container' => 'your-container',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutContainer()
    {
        $connector = $this->getRackspaceConnector();

        $connector->connect([
            'endpoint'  => 'https://lon.identity.api.rackspacecloud.com/v2.0/',
            'region'    => 'LON',
            'username'  => 'your-username',
            'apiKey'    => 'your-api-key',
        ]);
    }

    protected function getRackspaceConnector()
    {
        return new RackspaceConnector();
    }
}
