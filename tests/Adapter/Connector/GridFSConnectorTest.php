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

use GrahamCampbell\Flysystem\Adapter\Connector\GridFSConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use InvalidArgumentException;
use League\Flysystem\GridFS\GridFSAdapter;
use MongoConnectionException;

/**
 * This is the gridfs connector test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
 */
class GridFSConnectorTest extends AbstractTestCase
{
    /**
     * @requires extension mongodb
     */
    public function testConnectStandard()
    {
        $connector = $this->getGridFSConnector();

        try {
            $return = $connector->connect([
                'server'   => 'mongodb://localhost:27017',
                'database' => 'your-database',
            ]);

            $this->assertInstanceOf(GridFSAdapter::class, $return);
        } catch (MongoConnectionException $e) {
            $this->markTestSkipped('No mongo serer running');
        }
    }

    /**
     * @requires extension mongodb
     */
    public function testConnectWithoutDatabase()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The gridfs connector requires database configuration.');

        $connector = $this->getGridFSConnector();

        $connector->connect(['server' => 'mongodb://localhost:27017']);
    }

    public function testConnectWithoutServer()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The gridfs connector requires server configuration.');

        $connector = $this->getGridFSConnector();

        $connector->connect(['database' => 'your-database']);
    }

    protected function getGridFSConnector()
    {
        return new GridFSConnector();
    }
}
