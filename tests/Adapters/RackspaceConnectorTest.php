<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Adapters;

use GrahamCampbell\Flysystem\Adapters\RackspaceConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use Guzzle\Http\Exception\CurlException;

/**
 * This is the rackspace connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class RackspaceConnectorTest extends AbstractTestCase
{
    public function testConnect()
    {
        $this->expectException(\Guzzle\Http\Exception\ClientErrorResponseException::class);
        $connector = $this->getRackspaceConnector();

        try {
            $connector->connect([
                'endpoint'  => 'https://lon.identity.api.rackspacecloud.com/v2.0/',
                'region'    => 'LON',
                'username'  => 'your-username',
                'apiKey'    => 'your-api-key',
                'container' => 'your-container',
            ]);
        } catch (CurlException $e) {
            $this->markTestSkipped('No internet connection');
        }
    }

    public function testConnectWithoutEndpoint()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The rackspace connector requires endpoint configuration.');

        $connector = $this->getRackspaceConnector();

        $connector->connect([
            'region'    => 'LON',
            'username'  => 'your-username',
            'apiKey'    => 'your-api-key',
            'container' => 'your-container',
        ]);
    }

    public function testConnectWithoutRegion()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The rackspace connector requires region configuration.');

        $connector = $this->getRackspaceConnector();

        $connector->connect([
            'endpoint'  => 'https://lon.identity.api.rackspacecloud.com/v2.0/',
            'username'  => 'your-username',
            'apiKey'    => 'your-api-key',
            'container' => 'your-container',
        ]);
    }

    public function testConnectWithoutUsername()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The rackspace connector requires authentication.');

        $connector = $this->getRackspaceConnector();

        $connector->connect([
            'endpoint'  => 'https://lon.identity.api.rackspacecloud.com/v2.0/',
            'region'    => 'LON',
            'apiKey'    => 'your-api-key',
            'container' => 'your-container',
        ]);
    }

    public function testConnectWithoutApiKey()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The rackspace connector requires authentication.');

        $connector = $this->getRackspaceConnector();

        $connector->connect([
            'endpoint'  => 'https://lon.identity.api.rackspacecloud.com/v2.0/',
            'region'    => 'LON',
            'username'  => 'your-username',
            'container' => 'your-container',
        ]);
    }

    public function testConnectWithoutContainer()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The rackspace connector requires container configuration.');

        $connector = $this->getRackspaceConnector();

        $connector->connect([
            'endpoint'  => 'https://lon.identity.api.rackspacecloud.com/v2.0/',
            'region'    => 'LON',
            'username'  => 'your-username',
            'apiKey'    => 'your-api-key',
        ]);
    }

    public function testConnectWithInternal()
    {
        $this->expectException(\Guzzle\Http\Exception\ClientErrorResponseException::class);

        $connector = $this->getRackspaceConnector();

        try {
            $connector->connect([
                'endpoint'  => 'https://lon.identity.api.rackspacecloud.com/v2.0/',
                'region'    => 'LON',
                'username'  => 'your-username',
                'apiKey'    => 'your-api-key',
                'container' => 'your-container',
                'internal'  => true,
            ]);
        } catch (CurlException $e) {
            $this->markTestSkipped('No internet connection');
        }
    }

    public function testConnectWithInternalFalse()
    {
        $this->expectException(\Guzzle\Http\Exception\ClientErrorResponseException::class);

        $connector = $this->getRackspaceConnector();

        try {
            $connector->connect([
                'endpoint'  => 'https://lon.identity.api.rackspacecloud.com/v2.0/',
                'region'    => 'LON',
                'username'  => 'your-username',
                'apiKey'    => 'your-api-key',
                'container' => 'your-container',
                'internal'  => false,
            ]);
        } catch (CurlException $e) {
            $this->markTestSkipped('No internet connection');
        }
    }

    protected function getRackspaceConnector()
    {
        return new RackspaceConnector();
    }
}
