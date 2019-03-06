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

use GrahamCampbell\Flysystem\Adapters\LocalConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use InvalidArgumentException;
use League\Flysystem\Adapter\Local;

/**
 * This is the local connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class LocalConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getLocalConnector();

        $return = $connector->connect(['path' => __DIR__]);

        $this->assertInstanceOf(Local::class, $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getLocalConnector();

        $return = $connector->connect(['path' => __DIR__, 'prefix' => 'your-prefix']);

        $this->assertInstanceOf(Local::class, $return);
    }

    public function testConnectWithoutPath()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The local connector requires path configuration.');

        $connector = $this->getLocalConnector();

        $connector->connect([]);
    }

    protected function getLocalConnector()
    {
        return new LocalConnector();
    }
}
