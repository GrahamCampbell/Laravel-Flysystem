<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Flysystem;

use GrahamCampbell\Flysystem\Factories\FlysystemFactory;
use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Config\Repository;

/**
 * This is the flysystem manager class.
 *
 * @method bool put(string $path, string $contents, mixed $visibility = null)
 * @method bool putStream(string $path, resource $resource, mixed $visibility = null)
 * @method string readAndDelete(string $path)
 * @method array listPaths(string $directory = '', bool $recursive = false)
 * @method array listWith(array $keys = array(), string $directory = '', bool $recursive = false)
 * @method array getWithMetadata(string $path, array $metadata)
 * @method \League\Flysystem\Handler get(string $path, \League\Flysystem\Handler $handler = null)
 * @method \League\Flysystem\FilesystemInterface flushCache()
 * @method \League\Flysystem\FilesystemInterface addPlugin(\League\Flysystem\PluginInterface $plugin)
 * @method false|array write(string $path, string $contents, mixed $config = null)
 * @method false|array update(string $path, string $contents, mixed $config = null)
 * @method false|array writeStream(string $path, resource $resource, mixed $config = null)
 * @method false|array updateStream(string $path, resource $resource, mixed $config = null)
 * @method bool rename(string $path, string $newpath)
 * @method bool copy(string $path, string $newpath)
 * @method bool delete(string $path)
 * @method bool deleteDir(string $dirname)
 * @method bool createDir(string $dirname, array $options = null)
 * @method bool setVisibility(string $path, string $visibility)
 * @method bool has(string $path)
 * @method string|false read(string $path)
 * @method resource|false readStream(string $path)
 * @method false|array listContents(string $directory = '', bool $recursive = false)
 * @method false|array getMetadata(string $path)
 * @method int|false getSize(string $path)
 * @method string|false getMimetype(string $path)
 * @method string|false getTimestamp(string $path)
 * @method string|false getVisibility(string $path)
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class FlysystemManager extends AbstractManager
{
    /**
     * The factory instance.
     *
     * @var \GrahamCampbell\Flysystem\Factories\FlysystemFactory
     */
    protected $factory;

    /**
     * Create a new flysystem manager instance.
     *
     * @param \Illuminate\Config\Repository                        $config
     * @param \GrahamCampbell\Flysystem\Factories\FlysystemFactory $factory
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
        return 'graham-campbell/flysystem';
    }

    /**
     * Get the configuration for a connection.
     *
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    public function getConnectionConfig($name)
    {
        $name = $name ?: $this->getDefaultConnection();

        $connections = $this->config->get($this->getConfigName().'::connections');

        if (!is_array($config = array_get($connections, $name)) && !$config) {
            throw new \InvalidArgumentException("Adapter [$name] not configured.");
        }

        if (is_string($cache = array_get($config, 'cache'))) {
            $config['cache'] = $this->getCacheConfig($cache);
        }

        $config['name'] = $name;

        return $config;
    }

    /**
     * Get the cache configuration.
     *
     * @param string $name
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function getCacheConfig($name)
    {
        $cache = $this->config->get($this->getConfigName().'::cache');

        if (!is_array($config = array_get($cache, $name)) && !$config) {
            throw new \InvalidArgumentException("Cache [$name] not configured.");
        }

        $config['name'] = $name;

        return $config;
    }

    /**
     * Get the factory instance.
     *
     * @return \GrahamCampbell\Flysystem\Factories\FlysystemFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }
}
