<?php

/**
 * This file is part of Laravel Flysystem by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://bit.ly/UWsjkb.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Tests\Flysystem\Functional;

use GrahamCampbell\Flysystem\Facades\Flysystem;
use GrahamCampbell\Tests\Flysystem\AbstractTestCase;
use Illuminate\Contracts\Foundation\Application;

/**
 * This is the local flysystem test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class LocalFlysystemTest extends AbstractTestCase
{
    /**
     * Additional application environment setup.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     *
     * @return void
     */
    protected function additionalSetup(Application $app)
    {
        $app['files']->deleteDirectory(realpath(__DIR__.'/../../').'/temp');

        $old = $app['config']->get('graham-campbell/flysystem::connections');

        $new = array_merge($old, array(
            'testing' => array(
                'driver' => 'local',
                'path'   => realpath(__DIR__.'/../../').'/temp',
            ),
        ));

        $app['config']->set('graham-campbell/flysystem::connections', $new);
        $app['config']->set('graham-campbell/flysystem::default', 'testing');
    }

    /**
     * Run extra tear down code.
     *
     * @return void
     */
    protected function finish()
    {
        $this->app['files']->deleteDirectory(realpath(__DIR__.'/../../').'/temp');
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
