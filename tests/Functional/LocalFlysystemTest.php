<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Functional;

use GrahamCampbell\Flysystem\Facades\Flysystem;
use GrahamCampbell\Tests\Flysystem\AbstractTestCase;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\AdapterInterface;
use League\Flysystem\NotSupportedException;

/**
 * This is the local flysystem test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
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

    /**
     * @requires OS Linux
     */
    public function testCustomPermissions()
    {
        try {
            $this->app->files->deleteDirectory(realpath(__DIR__.'/../../').'/temp');

            $old = $this->app->config->get('flysystem.connections');

            $new = array_merge($old, [
                'testing' => [
                    'driver'      => 'local',
                    'path'        => realpath(__DIR__.'/../../').'/temp',
                    'permissions' => [
                        'file' => [
                            'public'  => 0666,
                            'private' => 0600,
                        ],
                        'dir' => [
                            'public'  => 0777,
                            'private' => 0700,
                        ],
                    ],
                ],
            ]);

            $this->app->config->set('flysystem.connections', $new);
            $this->app->config->set('flysystem.default', 'testing');

            Flysystem::put('public-file', 'bar', ['visibility' => AdapterInterface::VISIBILITY_PUBLIC]);
            Flysystem::put('private-file', 'bar', ['visibility' => AdapterInterface::VISIBILITY_PRIVATE]);

            Flysystem::createDir('public-dir', ['visibility' => AdapterInterface::VISIBILITY_PUBLIC]);
            Flysystem::createDir('private-dir', ['visibility' => AdapterInterface::VISIBILITY_PRIVATE]);

            $this->assertSame('666', self::getMask(Flysystem::getAdapter()->applyPathPrefix('public-file')));
            $this->assertSame('600', self::getMask(Flysystem::getAdapter()->applyPathPrefix('private-file')));
            $this->assertSame('777', self::getMask(Flysystem::getAdapter()->applyPathPrefix('public-dir')));
            $this->assertSame('700', self::getMask(Flysystem::getAdapter()->applyPathPrefix('private-dir')));
        } finally {
            $this->app->files->deleteDirectory(realpath(__DIR__.'/../../').'/temp');
        }
    }

    /**
     * @requires OS Linux
     */
    public function testCustomSymlinkSettings()
    {
        $linkHandlingFailed = false;

        try {
            $this->app->files->deleteDirectory(realpath(__DIR__.'/../../').'/temp');

            $old = $this->app->config->get('flysystem.connections');

            $new = array_merge($old, [
                'testing' => [
                    'driver'        => 'local',
                    'path'          => realpath(__DIR__.'/../../').'/temp',
                    'link_handling' => LocalAdapter::SKIP_LINKS,
                ],
            ]);

            $this->app->config->set('flysystem.connections', $new);
            $this->app->config->set('flysystem.default', 'testing');

            Flysystem::put('foo', 'foo');
            symlink(Flysystem::getAdapter()->applyPathPrefix('foo'), Flysystem::getAdapter()->applyPathPrefix('bar'));

            // Will throw an exception if custom link handling isn't set correctly
            Flysystem::get('bar');
        } catch (NotSupportedException $e) {
            // If the link handling failed then catch it and set a flag to be
            // consumed by the assertion below
            $linkHandlingFailed = true;
        } finally {
            $this->assertFalse($linkHandlingFailed);
            $this->app->files->deleteDirectory(realpath(__DIR__.'/../../').'/temp');
        }
    }

    /**
     * Get the permissions mask of the file/directory specified.
     *
     * @param string $file file or directory name
     *
     * @return int
     */
    protected static function getMask($file)
    {
        clearstatcache();

        return decoct(fileperms($file) & 0777);
    }
}
