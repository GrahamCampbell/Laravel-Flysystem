<?php

declare(strict_types=1);

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <hello@gjcampbell.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Adapter\Connector;

use GrahamCampbell\Flysystem\Adapter\Connector\ZipConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use InvalidArgumentException;
use League\Flysystem\ZipArchive\ZipArchiveAdapter;

/**
 * This is the zip connector test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class ZipConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getZipConnector();

        $return = $connector->connect(['path' => __DIR__.'/stubs/test.zip']);

        $this->assertInstanceOf(ZipArchiveAdapter::class, $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getZipConnector();

        $return = $connector->connect(['path' => __DIR__.'/stubs/test.zip', 'prefix' => 'your-prefix']);

        $this->assertInstanceOf(ZipArchiveAdapter::class, $return);
    }

    public function testConnectWithoutPath()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The zip connector requires path configuration.');

        $connector = $this->getZipConnector();

        $connector->connect([]);
    }

    protected function getZipConnector()
    {
        return new ZipConnector();
    }
}
