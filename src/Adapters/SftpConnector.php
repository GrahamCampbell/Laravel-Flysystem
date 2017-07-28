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

namespace GrahamCampbell\Flysystem\Adapters;

use GrahamCampbell\Manager\ConnectorInterface;
use League\Flysystem\Sftp\SftpAdapter;

/**
 * This is the sftp connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class SftpConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\Sftp\SftpAdapter
     */
    public function connect(array $config)
    {
        return $this->getAdapter($config);
    }

    /**
     * Get the sftp adapter.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\Sftp\SftpAdapter
     */
    protected function getAdapter(array $config)
    {
        return new SftpAdapter($config);
    }
}
