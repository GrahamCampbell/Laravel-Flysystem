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

use GrahamCampbell\Flysystem\Adapters\WebDavConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the webdav connector test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class WebDavConnectorTest extends AbstractTestCase
{
    public function testConnect()
    {
        $connector = $this->getWebDavConnector();

        $return = $connector->connect([
            'baseUri'  => 'http://example.org/dav/',
            'userName' => 'your-username',
            'password' => 'your-password',
        ]);

        $this->assertInstanceOf('League\Flysystem\WebDAV\Adapter', $return);
    }

    protected function getWebDavConnector()
    {
        return new WebDavConnector();
    }
}
