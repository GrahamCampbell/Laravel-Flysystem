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

namespace GrahamCampbell\Tests\Flysystem\Adapters;

use GrahamCampbell\Flysystem\Adapters\FtpConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use League\Flysystem\Adapter\Ftp;

/**
 * This is the ftp connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class FtpConnectorTest extends AbstractTestCase
{
    public function testConnect()
    {
        $connector = $this->getFtpConnector();

        $return = $connector->connect([
            'host'     => 'ftp.example.com',
            'port'     => 21,
            'username' => 'your-username',
            'password' => 'your-password',
        ]);

        $this->assertInstanceOf(Ftp::class, $return);
    }

    protected function getFtpConnector()
    {
        return new FtpConnector();
    }
}
