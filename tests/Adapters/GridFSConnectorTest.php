<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Adapters;

use GrahamCampbell\Flysystem\Adapters\GridFSConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use MongoConnectionException;

/**
 * This is the gridfs connector test class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class GridFSConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        if (!class_exists('MongoClient')) {
            $this->markTestSkipped('The MongoClient class does not exist');
        }

        $connector = $this->getGridFSConnector();

        try {
            $return = $connector->connect([
                'server'   => 'mongodb://localhost:27017',
                'database' => 'your-database',
            ]);

            $this->assertInstanceOf('League\Flysystem\GridFS\GridFSAdapter', $return);
        } catch (MongoConnectionException $e) {
            $this->markTestSkipped('No mongo serer running');
        }
    }

    /**
     * @depends testConnectStandard
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutDatabase()
    {
        $connector = $this->getGridFSConnector();

        $connector->connect([
            'server' => 'mongodb://localhost:27017',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutServer()
    {
        $connector = $this->getGridFSConnector();

        $connector->connect([
            'database' => 'your-database',
        ]);
    }

    protected function getGridFSConnector()
    {
        return new GridFSConnector();
    }
}
