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

namespace GrahamCampbell\Flysystem;

use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Arr;

/**
 * This is the flysystem manager class.
 *
 * @method \League\Flysystem\FilesystemInterface               connection(string|null $name = null)
 * @method \League\Flysystem\FilesystemInterface               reconnect(string|null $name = null)
 * @method void                                                disconnect(string|null $name = null)
 * @method array<string,\League\Flysystem\FilesystemInterface> getConnections()
 * @method bool                                                has(string $path)
 * @method false|string                                        read(string $path)
 * @method false|resource                                      readStream(string $path)
 * @method array                                               listContents(string $directory = '', bool $recursive = false)
 * @method false|array                                         getMetadata(string $path)
 * @method false|int                                           getSize(string $path)
 * @method false|string                                        getMimetype(string $path)
 * @method false|int                                           getTimestamp(string $path)
 * @method false|string                                        getVisibility(string $path)
 * @method bool                                                write(string $path, string $contents, array $config = [])
 * @method bool                                                writeStream(string $path, resource $resource, array $config = [])
 * @method bool                                                update(string $path, string $contents, array $config = [])
 * @method bool                                                updateStream(string $path, resource $resource, array $config = [])
 * @method bool                                                rename(string $path, string $newpath)
 * @method bool                                                copy(string $path, string $newpath)
 * @method bool                                                delete(string $path)
 * @method bool                                                deleteDir(string $dirname)
 * @method bool                                                createDir(string $dirname, array $config = [])
 * @method bool                                                setVisibility(string $path, string $visibility)
 * @method bool                                                put(string $path, string $contents, array $config = [])
 * @method bool                                                putStream(string $path, resource $resource, array $config = [])
 * @method string                                              readAndDelete(string $path)
 * @method \League\Flysystem\Handler                           get(string $path, \League\Flysystem\Handler $handler = null)
 * @method \League\Flysystem\FilesystemInterface               addPlugin(\League\Flysystem\PluginInterface $plugin)
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class FlysystemManager extends AbstractManager
{
    /**
     * The factory instance.
     *
     * @var \GrahamCampbell\Flysystem\FlysystemFactory
     */
    protected $factory;

    /**
     * Create a new flysystem manager instance.
     *
     * @param \Illuminate\Contracts\Config\Repository    $config
     * @param \GrahamCampbell\Flysystem\FlysystemFactory $factory
     *
     * @return void
     */
    public function __construct(Repository $config, FlysystemFactory $factory)
    {
        $this->config = $config;
        $this->factory = $factory;
    }

    /**
     * Create the connection instance.
     *
     * @param array $config
     *
     * @return \League\Flysystem\FilesystemInterface
     */
    protected function createConnection(array $config)
    {
        return $this->factory->make($config, $this);
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected function getConfigName()
    {
        return 'flysystem';
    }

    /**
     * Get the configuration for a connection.
     *
     * @param string|null $name
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function getConnectionConfig(string $name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        $config = $this->getNamedConfig('connections', 'Adapter', $name);

        if (is_string($cache = Arr::get($config, 'cache'))) {
            $config['cache'] = $this->getNamedConfig('cache', 'Cache', $cache);
        }

        return $config;
    }

    /**
     * Get the factory instance.
     *
     * @return \GrahamCampbell\Flysystem\FlysystemFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }
}
