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

use GrahamCampbell\Flysystem\Adapters\MinIOConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use InvalidArgumentException;
use League\Flysystem\minio\minioAdapter;

/**
 * This is the minio connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Raul Ruiz <publiux@gmail.com>
 */
class MinIOConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getMinIOConnector();

        $return = $connector->connect([
            'key'     => 'your-key',
            'secret'  => 'your-secret',
            'bucket'  => 'your-bucket',
            'region'  => 'us-east-1',
            'version' => 'latest',
        ]);

        $this->assertInstanceOf(minioAdapter::class, $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getMinIOConnector();

        $return = $connector->connect([
            'key'     => 'your-key',
            'secret'  => 'your-secret',
            'bucket'  => 'your-bucket',
            'region'  => 'us-east-1',
            'version' => 'latest',
            'prefix'  => 'your-prefix',
        ]);

        $this->assertInstanceOf(minioAdapter::class, $return);
    }

    public function testConnectWithBucketEndPoint()
    {
        $connector = $this->getMinIOConnector();

        $return = $connector->connect([
            'key'             => 'your-key',
            'secret'          => 'your-secret',
            'bucket'          => 'your-bucket',
            'region'          => 'us-east-1',
            'version'         => 'latest',
            'bucket_endpoint' => false,
        ]);

        $this->assertInstanceOf(minioAdapter::class, $return);
    }

    public function testConnectWithCalculateMD5()
    {
        $connector = $this->getMinIOConnector();

        $return = $connector->connect([
            'key'           => 'your-key',
            'secret'        => 'your-secret',
            'bucket'        => 'your-bucket',
            'region'        => 'us-east-1',
            'version'       => 'latest',
            'calculate_md5' => true,
        ]);

        $this->assertInstanceOf(minioAdapter::class, $return);
    }

    public function testConnectWithScheme()
    {
        $connector = $this->getMinIOConnector();

        $return = $connector->connect([
            'key'     => 'your-key',
            'secret'  => 'your-secret',
            'bucket'  => 'your-bucket',
            'region'  => 'us-east-1',
            'version' => 'latest',
            'scheme'  => 'https',
        ]);

        $this->assertInstanceOf(minioAdapter::class, $return);
    }

    public function testConnectWithEndPoint()
    {
        $connector = $this->getMinIOConnector();

        $return = $connector->connect([
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'region'   => 'us-east-1',
            'version'  => 'latest',
            'endpoint' => 'https://example.com',
        ]);

        $this->assertInstanceOf(minioAdapter::class, $return);
    }

    public function testConnectWithEverything()
    {
        $connector = $this->getMinIOConnector();

        $return = $connector->connect([
            'key'             => 'your-key',
            'secret'          => 'your-secret',
            'bucket'          => 'your-bucket',
            'region'          => 'your-region',
            'version'         => 'latest',
            'bucket_endpoint' => false,
            'calculate_md5'   => true,
            'scheme'          => 'https',
            'endpoint'        => 'https://example.com',
        ]);

        $this->assertInstanceOf(minioAdapter::class, $return);
    }

    public function testConnectWithoutBucket()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minio connector requires bucket configuration.');

        $connector = $this->getMinIOConnector();

        $connector->connect([
            'key'     => 'your-key',
            'secret'  => 'your-secret',
            'region'  => 'us-east-1',
            'version' => 'latest',
        ]);
    }

    public function testConnectWithoutKey()
    {
        $connector = $this->getMinIOConnector();

        $return = $connector->connect([
            'secret'  => 'your-secret',
            'bucket'  => 'your-bucket',
            'region'  => 'us-east-1',
            'version' => 'latest',
        ]);

        $this->assertInstanceOf(minioAdapter::class, $return);
    }

    public function testConnectWithoutSecret()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minio connector requires authentication.');

        $connector = $this->getMinIOConnector();

        $connector->connect([
            'key'     => 'your-key',
            'bucket'  => 'your-bucket',
            'region'  => 'us-east-1',
            'version' => 'latest',
        ]);
    }

    public function testConnectWithoutVersion()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minio connector requires version configuration.');

        $connector = $this->getMinIOConnector();

        $connector->connect([
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
            'region' => 'us-east-1',
        ]);
    }

    public function testConnectWithoutRegion()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minio connector requires region configuration.');

        $connector = $this->getMinIOConnector();

        $connector->connect([
            'key'     => 'your-key',
            'secret'  => 'your-secret',
            'bucket'  => 'your-bucket',
            'version' => 'latest',
        ]);
    }

    protected function getMinIOConnector()
    {
        return new MinIOConnector();
    }
}
