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

use GrahamCampbell\Flysystem\Adapters\zipConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the zip connector test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class ZipConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getZipConnector();

        $return = $connector->connect(['path' => __DIR__.'\stubs\test.zip']);

        $this->assertInstanceOf('League\Flysystem\Adapter\Zip', $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getZipConnector();

        $return = $connector->connect(['path' => __DIR__.'\stubs\test.zip', 'prefix' => 'your-prefix']);

        $this->assertInstanceOf('League\Flysystem\Adapter\Zip', $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutPath()
    {
        $connector = $this->getZipConnector();

        $connector->connect([]);
    }

    protected function getZipConnector()
    {
        return new ZipConnector();
    }
}
