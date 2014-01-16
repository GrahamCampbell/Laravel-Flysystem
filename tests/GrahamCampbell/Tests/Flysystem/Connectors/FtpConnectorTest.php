<?php

/**
 * This file is part of Laravel Flysystem by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Tests\Flysystem\Classes;

use Mockery;
use GrahamCampbell\Flysystem\Connectors\FtpConnector;
use GrahamCampbell\TestBench\Classes\AbstractTestCase;

/**
 * This is the ftp connector test class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/develop/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class FtpConnectorTest extends AbstractTestCase
{
    public function testConnect()
    {
        $connector = $this->getFtpConnector();

        $return = $connector->connect(array(
            'host' => 'ftp.example.com',
            'port' => 21,
            'username' => 'your-username',
            'password' => 'your-password'
        ));

        $this->assertInstanceOf('League\Flysystem\Adapter\Ftp', $return);
    }

    protected function getFtpConnector()
    {
        return new FtpConnector();
    }
}
