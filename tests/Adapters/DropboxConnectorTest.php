<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Adapters;

use GrahamCampbell\Flysystem\Adapters\DropboxConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use Srmklive\Dropbox\Adapter\DropboxAdapter;

/**
 * This is the dropbox connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DropboxConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getDropboxConnector();

        $return = $connector->connect([
            'token'  => 'your-token',
        ]);

        $this->assertInstanceOf(DropboxAdapter::class, $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getDropboxConnector();

        $return = $connector->connect([
            'token'  => 'your-token',
            'prefix' => 'your-prefix',
        ]);

        $this->assertInstanceOf(DropboxAdapter::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The dropbox connector requires authentication.
     */
    public function testConnectWithoutToken()
    {
        $connector = $this->getDropboxConnector();

        $connector->connect(['app' => 'your-app']);
    }

    protected function getDropboxConnector()
    {
        return new DropboxConnector();
    }
}
