<?php

/**
 * This file is part of Laravel Flysystem by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
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

/**
 * This is the local flysystem test class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class LocalFlysystemTest extends AbstractTestCase
{
    /**
     * Additional application environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function additionalSetup($app)
    {
        $app['files']->deleteDirectory(realpath($this->getBasePath().'/../') . '/temp');

        $old = $app['config']->get('graham-campbell/flysystem::connections');

        $new = array_merge($old, array(
            'testing' => array(
                'driver' => 'local',
                'path'   => realpath($this->getBasePath().'/../') . '/temp'
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
        $this->app['files']->deleteDirectory(realpath($this->getBasePath().'/../') . '/temp');
    }

    public function testName()
    {
        $this->assertEquals('testing', Flysystem::getDefaultConnection());
    }

    public function testActions()
    {
        $this->assertFalse(Flysystem::has('foo'));

        Flysystem::put('foo', 'bar');

        $this->assertTrue(Flysystem::has('foo'));

        $this->assertEquals('bar', Flysystem::read('foo'));

        Flysystem::delete('foo');

        $this->assertFalse(Flysystem::has('foo'));
    }
}
