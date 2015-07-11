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

use GrahamCampbell\Flysystem\Adapters\NullConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use League\Flysystem\Adapter\NullAdapter;

/**
 * This is the null connector test class.
 *
 * @author Graham Campbell <graham@cachethq.io>
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
