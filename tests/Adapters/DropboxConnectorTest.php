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

use GrahamCampbell\Flysystem\Adapters\DropboxConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the dropbox connector test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
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

        $this->assertInstanceOf('League\Flysystem\Adapter\Dropbox', $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getDropboxConnector();

        $return = $connector->connect([
            'token'  => 'your-token',
            'app'    => 'your-app',
            'prefix' => 'your-prefix',
        ]);

        $this->assertInstanceOf('League\Flysystem\Adapter\Dropbox', $return);
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
