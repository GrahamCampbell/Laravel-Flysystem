<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Functional;

use GrahamCampbell\Flysystem\Facades\Flysystem;
use GrahamCampbell\Tests\Flysystem\AbstractTestCase;

/**
 * This is the local flysystem test class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class LocalFlysystemTest extends AbstractTestCase
{
    public function testStuff()
    {
        try {
            $this->app->files->deleteDirectory(realpath(__DIR__.'/../../').'/temp');

            $old = $this->app->config->get('flysystem.connections');

            $new = array_merge($old, [
                'testing' => [
                    'driver' => 'local',
                    'path'   => realpath(__DIR__.'/../../').'/temp',
                ],
            ]);

            $this->app->config->set('flysystem.connections', $new);
            $this->app->config->set('flysystem.default', 'testing');

            $this->assertSame('testing', Flysystem::getDefaultConnection());

            $this->assertFalse(Flysystem::has('foo'));

            Flysystem::put('foo', 'bar');

            $this->assertTrue(Flysystem::has('foo'));

            $this->assertSame('bar', Flysystem::read('foo'));

            Flysystem::delete('foo');

            $this->assertFalse(Flysystem::has('foo'));
        } finally {
            $this->app->files->deleteDirectory(realpath(__DIR__.'/../../').'/temp');
        }
    }
}
