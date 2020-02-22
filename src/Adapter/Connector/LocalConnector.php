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

namespace GrahamCampbell\Flysystem\Adapter\Connector;

use GrahamCampbell\Manager\ConnectorInterface;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use League\Flysystem\Adapter\Local;

/**
 * This is the local connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
final class LocalConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \League\Flysystem\Adapter\Local
     */
    public function connect(array $config)
    {
        $config = self::getConfig($config);

        return self::getAdapter($config);
    }

    /**
     * Get the configuration.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return string[]
     */
    private static function getConfig(array $config)
    {
        if (!array_key_exists('path', $config)) {
            throw new InvalidArgumentException('The local connector requires path configuration.');
        }

        return Arr::only($config, ['path', 'write_flags', 'link_handling', 'permissions']);
    }

    /**
     * Get the local adapter.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\Adapter\Local
     */
    private static function getAdapter(array $config)
    {
        // Pull parameters from config and set defaults for optional values
        $path = $config['path'];
        $writeFlags = Arr::get($config, 'write_flags', LOCK_EX);
        $linkHandling = Arr::get($config, 'link_handling', Local::DISALLOW_LINKS);
        $permissions = Arr::get($config, 'permissions', []);

        return new Local($path, $writeFlags, $linkHandling, $permissions);
    }
}
