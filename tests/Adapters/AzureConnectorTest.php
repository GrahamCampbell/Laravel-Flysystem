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

use GrahamCampbell\Flysystem\Adapters\AzureConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the adapter connector test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AzureConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getAzureConnector();

        $return = $connector->connect([
            'account-name' => 'your-account-name',
            'api-key'      => 'eW91ci1hcGkta2V5',
            'container'    => 'your-container',
        ]);

        $this->assertInstanceOf('League\Flysystem\Azure\Adapter', $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutAccountName()
    {
        $connector = $this->getAzureConnector();

        $connector->connect([
            'api-key'   => 'eW91ci1hcGkta2V5',
            'container' => 'your-container',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutApiKey()
    {
        $connector = $this->getAzureConnector();

        $connector->connect([
            'account-name' => 'your-account-name',
            'container'    => 'your-container',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutContainer()
    {
        $connector = $this->getAzureConnector();

        $connector->connect([
            'account-name' => 'your-account-name',
            'api-key'      => 'eW91ci1hcGkta2V5',
        ]);
    }

    protected function getAzureConnector()
    {
        return new AzureConnector();
    }
}
