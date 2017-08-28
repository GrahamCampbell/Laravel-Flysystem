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

use GrahamCampbell\Flysystem\Adapters\BackblazeConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use Mhetreramesh\Flysystem\BackblazeAdapter;

/**
 * This is the backblaze connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Mattia Trapani <mattia.trapani@gmail.com>
 */
class BackblazeConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getBackblazeConnector();

        $return = $connector->connect([
            'accountId'      => 'your-account-id',
            'applicationKey' => 'your-application-key',
            'bucket'         => 'your-bucket',
        ]);

        $this->assertInstanceOf(BackblazeAdapter::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The backblaze connector requires bucket configuration.
     */
    public function testConnectWithoutBucket()
    {
        $connector = $this->getBackblazeConnector();

        $connector->connect([
            'accountId'      => 'your-account-id',
            'applicationKey' => 'your-application-key',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The backblaze connector requires accountId.
     */
    public function testConnectWithoutAccountId()
    {
        $connector = $this->getBackblazeConnector();

        $connector->connect([
            'applicationKey' => 'your-application-key',
            'bucket'         => 'your-bucket',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The backblaze connector requires applicationKey.
     */
    public function testConnectWithoutApplicationKey()
    {
        $connector = $this->getBackblazeConnector();

        $connector->connect([
            'accountId' => 'your-account-id',
            'bucket'    => 'your-bucket',
        ]);
    }

    protected function getBackblazeConnector()
    {
        return new BackblazeConnector();
    }
}
