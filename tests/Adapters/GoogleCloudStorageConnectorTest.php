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

use GrahamCampbell\Flysystem\Adapters\GoogleCloudStorageConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

/**
 * This is the awss3 connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Raul Ruiz <publiux@gmail.com>
 */
class GoogleCloudStorageConnectorTest extends AbstractTestCase
{
    protected static $testKeyFile = './tests/Adapters/stubs/gcs-auth.json';

    public function testConnectStandard()
    {
        $connector = $this->getGoogleCloudStorageConnector();

        $return = $connector->connect([
            'project_id'     => 'your-project-id',
            'key_file' => self::$testKeyFile,
            'bucket'  => 'your-bucket',
        ]);

        $this->assertInstanceOf(GoogleStorageAdapter::class, $return);
    }

    public function testConnectWithoutKey()
    {
        $connector = $this->getGoogleCloudStorageConnector();

        $return = $connector->connect([
            'project_id'     => 'your-project-id',
            'bucket'  => 'your-bucket',
        ]);

        $this->assertInstanceOf(GoogleStorageAdapter::class, $return);
    }

    public function testConnectWithPathPrefix()
    {
        $connector = $this->getGoogleCloudStorageConnector();

        $return = $connector->connect([
            'project_id'     => 'your-project-id',
            'bucket'  => 'your-bucket',
            'path_prefix' => 'your-path',
        ]);

        $this->assertInstanceOf(GoogleStorageAdapter::class, $return);
    }

    public function testConnectWithURI()
    {
        $connector = $this->getGoogleCloudStorageConnector();

        $return = $connector->connect([
            'project_id'     => 'your-project-id',
            'bucket'  => 'your-bucket',
            'storage_api_uri' => 'http://your-domain.com'
        ]);

        $this->assertInstanceOf(GoogleStorageAdapter::class, $return);
    }

    public function testConnectWithEverything()
    {
        $connector = $this->getGoogleCloudStorageConnector();

        $return = $connector->connect([
            'project_id'     => 'your-project-id',
            'key_file' => self::$testKeyFile,
            'bucket'  => 'your-bucket',
            'path_prefix' => 'your-path',
            'storage_api_uri' => 'http://your-domain.com'
        ]);

        $this->assertInstanceOf(GoogleStorageAdapter::class, $return);
    }

    protected function getGoogleCloudStorageConnector()
    {
        return new GoogleCloudStorageConnector();
    }
}
