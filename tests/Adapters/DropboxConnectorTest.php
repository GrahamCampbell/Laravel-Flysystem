<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Adapters;

use GrahamCampbell\Flysystem\Adapters\DropboxConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use League\Flysystem\Dropbox\DropboxAdapter;

/**
 * This is the dropbox connector test class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class DropboxConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getDropboxConnector();

        $return = $connector->connect([
            'token'  => 'your-token',
            'app'    => 'your-app',
        ]);

        $this->assertInstanceOf(DropboxAdapter::class, $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getDropboxConnector();

        $return = $connector->connect([
            'token'  => 'your-token',
            'app'    => 'your-app',
            'prefix' => 'your-prefix',
        ]);

        $this->assertInstanceOf(DropboxAdapter::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutToken()
    {
        $connector = $this->getDropboxConnector();

        $connector->connect(['app' => 'your-app']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutSecret()
    {
        $connector = $this->getDropboxConnector();

        $connector->connect(['token' => 'your-token']);
    }

    protected function getDropboxConnector()
    {
        return new DropboxConnector();
    }
}
