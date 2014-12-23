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

use GrahamCampbell\Flysystem\Adapters\WebDavConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the webdav connector test class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class WebDavConnectorTest extends AbstractTestCase
{
    public function testConnect()
    {
        $connector = $this->getWebDavConnector();

        $return = $connector->connect(array(
            'baseUri'  => 'http://example.org/dav/',
            'userName' => 'your-username',
            'password' => 'your-password',
        ));

        $this->assertInstanceOf('League\Flysystem\WebDAV\Adapter', $return);
    }

    protected function getWebDavConnector()
    {
        return new WebDavConnector();
    }
}
