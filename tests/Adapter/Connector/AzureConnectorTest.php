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

use GrahamCampbell\Flysystem\Adapter\Connector\AzureConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use InvalidArgumentException;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;

/**
 * This is the adapter connector test class.
 *
 * @author Graham Campbell <hello@gjcampbell.co.uk>
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

        $this->assertInstanceOf(AzureBlobStorageAdapter::class, $return);
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
