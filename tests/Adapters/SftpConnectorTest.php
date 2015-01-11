<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Adapters;

use GrahamCampbell\Flysystem\Adapters\SftpConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the sftp connector test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class SftpConnectorTest extends AbstractTestCase
{
    public function testConnect()
    {
        $connector = $this->getSftpConnector();

        $return = $connector->connect([
            'host'     => 'sftp.example.com',
            'port'     => 22,
            'username' => 'your-username',
            'password' => 'your-password',
        ]);

        $this->assertInstanceOf('League\Flysystem\Adapter\Sftp', $return);
    }

    protected function getSftpConnector()
    {
        return new SftpConnector();
    }
}
