<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Functional;

use GrahamCampbell\Flysystem\Facades\Flysystem;
use GrahamCampbell\Tests\Flysystem\AbstractTestCase;
use Illuminate\Contracts\Foundation\Application;

/**
 * This is the local flysystem test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class LocalFlysystemTest extends AbstractTestCase
{
    /**
     * Run extra setup code.
     *
     * @return void
     */
    protected function start()
    {
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
    }

    /**
     * Run extra tear down code.
     *
     * @return void
     */
    protected function finish()
    {
        $this->app->files->deleteDirectory(realpath(__DIR__.'/../../').'/temp');
    }

    public function testName()
    {
        $this->assertSame('testing', Flysystem::getDefaultConnection());
    }

    public function testActions()
    {
        $this->assertFalse(Flysystem::has('foo'));

        Flysystem::put('foo', 'bar');

        $this->assertTrue(Flysystem::has('foo'));

        $this->assertSame('bar', Flysystem::read('foo'));

        Flysystem::delete('foo');

        $this->assertFalse(Flysystem::has('foo'));
    }
}
