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

use GrahamCampbell\Flysystem\Adapters\AzureConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use InvalidArgumentException;
use League\Flysystem\Azure\AzureAdapter;

/**
 * This is the adapter connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
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

        $this->assertInstanceOf(AzureAdapter::class, $return);
    }

    public function testConnectWithoutAccountName()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The azure connector requires authentication.');

        $connector = $this->getAzureConnector();

        $connector->connect([
            'api-key'   => 'eW91ci1hcGkta2V5',
            'container' => 'your-container',
        ]);
    }

    public function testConnectWithoutApiKey()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The azure connector requires authentication.');

        $connector = $this->getAzureConnector();

        $connector->connect([
            'account-name' => 'your-account-name',
            'container'    => 'your-container',
        ]);
    }

    public function testConnectWithoutContainer()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The azure connector requires container configuration.');

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
