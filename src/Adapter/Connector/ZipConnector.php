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
use League\Flysystem\ZipArchive\ZipArchiveAdapter;

/**
 * This is the zip connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
final class ZipConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \League\Flysystem\ZipArchive\ZipArchiveAdapter
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
            throw new InvalidArgumentException('The zip connector requires path configuration.');
        }

        return Arr::only($config, ['path']);
    }

    /**
     * Get the zip adapter.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\ZipArchive\ZipArchiveAdapter
     */
    private static function getAdapter(array $config)
    {
        return new ZipArchiveAdapter($config['path']);
    }
}
