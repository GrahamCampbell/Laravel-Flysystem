<?php

/*
 * This file is part of Laravel Flysystem by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at http://bit.ly/UWsjkb.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Tests\Flysystem\Adapters;

use GrahamCampbell\Flysystem\Adapters\FtpConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the ftp connector test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class FtpConnectorTest extends AbstractTestCase
{
    public function testConnect()
    {
        if (!defined('FTP_BINARY')) {
            $this->markTestSkipped('The FTP_BINARY constant is not defined');

            return;
        }

        $connector = $this->getFtpConnector();

        $return = $connector->connect([
            'host'     => 'ftp.example.com',
            'port'     => 21,
            'username' => 'your-username',
            'password' => 'your-password',
        ]);

        $this->assertInstanceOf('League\Flysystem\Adapter\Ftp', $return);
    }

    protected function getFtpConnector()
    {
        return new FtpConnector();
    }
}
