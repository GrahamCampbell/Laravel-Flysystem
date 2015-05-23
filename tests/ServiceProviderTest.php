<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem;

use GrahamCampbell\TestBench\Traits\ServiceProviderTestCaseTrait;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTestCaseTrait;

    public function testFlysystemFactoryIsInjectable()
    {
        $this->assertIsInjectable('GrahamCampbell\Flysystem\Factories\FlysystemFactory');
    }

    public function testFlysystemManagerIsInjectable()
    {
        $this->assertIsInjectable('GrahamCampbell\Flysystem\FlysystemManager');
    }
}
