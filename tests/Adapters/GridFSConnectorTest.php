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

use GrahamCampbell\Flysystem\Adapters\GridFSConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the gridfs connector test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class GridFSConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        if (!class_exists('MongoClient')) {
            $this->markTestSkipped('The MongoClient class does not exist');
        }

        $connector = $this->getGridFSConnector();

        $return = $connector->connect([
            'server'   => 'mongodb://localhost:27017',
            'database' => 'your-database',
        ]);

        $this->assertInstanceOf('League\Flysystem\GridFS\GridFSAdapter', $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectServer()
    {
        $connector = $this->getGridFSConnector();

        $connector->connect([
            'database' => 'your-database',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutDatabase()
    {
        if (!class_exists('MongoClient')) {
            $this->markTestSkipped('The MongoClient class does not exist');
        }

        $connector = $this->getGridFSConnector();

        $connector->connect([
            'server' => 'mongodb://localhost:27017',
        ]);
    }

    protected function getGridFSConnector()
    {
        return new GridFSConnector();
    }
}
