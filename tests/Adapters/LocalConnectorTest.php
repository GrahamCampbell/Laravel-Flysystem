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

use GrahamCampbell\Flysystem\Adapters\LocalConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the local connector test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class LocalConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getLocalConnector();

        $return = $connector->connect(['path' => __DIR__]);

        $this->assertInstanceOf('League\Flysystem\Adapter\Local', $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getLocalConnector();

        $return = $connector->connect(['path' => __DIR__, 'prefix' => 'your-prefix']);

        $this->assertInstanceOf('League\Flysystem\Adapter\Local', $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutPath()
    {
        $connector = $this->getLocalConnector();

        $connector->connect([]);
    }

    protected function getLocalConnector()
    {
        return new LocalConnector();
    }
}
