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

namespace GrahamCampbell\Tests\Flysystem\Adapter\Connector;

use GrahamCampbell\Flysystem\Adapter\Connector\GoogleCloudStorageConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

/**
 * This is the awss3 connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Raul Ruiz <publiux@gmail.com>
 * @author Nir Radian <nirradi@gmail.com>
 */
class GoogleCloudStorageConnectorTest extends AbstractTestCase
{
    protected static $testKeyFile = __DIR__.'/stubs/gcs-auth.json';

    public function testConnectStandard()
    {
        $connector = $this->getGoogleCloudStorageConnector();

        $return = $connector->connect([
            'projectId' => 'your-project-id',
            'keyFile'   => static::$testKeyFile,
            'bucket'    => 'your-bucket',
        ]);

        $this->assertInstanceOf(GoogleStorageAdapter::class, $return);
    }

    public function testConnectWithoutKey()
    {
        $connector = $this->getGoogleCloudStorageConnector();

        $return = $connector->connect([
            'projectId' => 'your-project-id',
            'bucket'    => 'your-bucket',
        ]);

        $this->assertInstanceOf(GoogleStorageAdapter::class, $return);
    }

    public function testConnectWithPathPrefix()
    {
        $connector = $this->getGoogleCloudStorageConnector();

        $return = $connector->connect([
            'projectId' => 'your-project-id',
            'bucket'    => 'your-bucket',
            'prefix'    => 'your-path',
        ]);

        $this->assertInstanceOf(GoogleStorageAdapter::class, $return);
    }

    public function testConnectWithURI()
    {
        $connector = $this->getGoogleCloudStorageConnector();

        $return = $connector->connect([
            'projectId' => 'your-project-id',
            'bucket'    => 'your-bucket',
            'apiUri'    => 'http://your-domain.com',
        ]);

        $this->assertInstanceOf(GoogleStorageAdapter::class, $return);
    }

    public function testConnectWithEverything()
    {
        $connector = $this->getGoogleCloudStorageConnector();

        $return = $connector->connect([
            'projectId' => 'your-project-id',
            'keyFile'   => static::$testKeyFile,
            'bucket'    => 'your-bucket',
            'prefix'    => 'your-path',
            'apiUri'    => 'http://your-domain.com',
        ]);

        $this->assertInstanceOf(GoogleStorageAdapter::class, $return);
    }

    protected function getGoogleCloudStorageConnector()
    {
        return new GoogleCloudStorageConnector();
    }
}
