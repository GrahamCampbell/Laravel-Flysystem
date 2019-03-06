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

use GrahamCampbell\Flysystem\Adapters\DropboxConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use InvalidArgumentException;
use Spatie\FlysystemDropbox\DropboxAdapter;

/**
 * This is the dropbox connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DropboxConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getDropboxConnector();

        $return = $connector->connect([
            'token'  => 'your-token',
        ]);

        $this->assertInstanceOf(DropboxAdapter::class, $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getDropboxConnector();

        $return = $connector->connect([
            'token'  => 'your-token',
            'prefix' => 'your-prefix',
        ]);

        $this->assertInstanceOf(DropboxAdapter::class, $return);
    }

    public function testConnectWithoutToken()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The dropbox connector requires authentication.');

        $connector = $this->getDropboxConnector();

        $connector->connect(['app' => 'your-app']);
    }

    protected function getDropboxConnector()
    {
        return new DropboxConnector();
    }
}
