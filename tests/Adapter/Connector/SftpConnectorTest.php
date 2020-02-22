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

namespace GrahamCampbell\Tests\Flysystem\Adapter\Connector;

use GrahamCampbell\Flysystem\Adapter\Connector\SftpConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use League\Flysystem\Sftp\SftpAdapter;

/**
 * This is the sftp connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
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

        $this->assertInstanceOf(SftpAdapter::class, $return);
    }

    protected function getSftpConnector()
    {
        return new SftpConnector();
    }
}
