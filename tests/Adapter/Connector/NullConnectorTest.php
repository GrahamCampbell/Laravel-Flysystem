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

namespace GrahamCampbell\Tests\Flysystem\Adapter\Connector;

use GrahamCampbell\Flysystem\Adapter\Connector\NullConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use League\Flysystem\Adapter\NullAdapter;

/**
 * This is the null connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class NullConnectorTest extends AbstractTestCase
{
    public function testConnect()
    {
        $connector = $this->getNullConnector();

        $return = $connector->connect([]);

        $this->assertInstanceOf(NullAdapter::class, $return);
    }

    protected function getNullConnector()
    {
        return new NullConnector();
    }
}
