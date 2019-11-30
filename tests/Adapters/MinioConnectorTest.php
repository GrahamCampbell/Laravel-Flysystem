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

use GrahamCampbell\Flysystem\Adapters\MinioConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use InvalidArgumentException;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

/**
 * This is the minio connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Raul Ruiz <publiux@gmail.com>
 * @author Reza Seyf <rseyf2017@gmail.com>
 */
class MinioConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getMinioConnector();

        $return = $connector->connect([
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'region'   => 'us-east-1',
            'endpoint' => 'https://example.com',
            'version'  => 'latest',
        ]);

        $this->assertInstanceOf(AwsS3Adapter::class, $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getMinioConnector();

        $return = $connector->connect([
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'region'   => 'us-east-1',
            'endpoint' => 'https://example.com',
            'version'  => 'latest',
            'prefix'   => 'your-prefix',
        ]);

        $this->assertInstanceOf(AwsS3Adapter::class, $return);
    }

    public function testConnectWithBucketEndPoint()
    {
        $connector = $this->getMinioConnector();

        $return = $connector->connect([
            'key'             => 'your-key',
            'secret'          => 'your-secret',
            'bucket'          => 'your-bucket',
            'region'          => 'us-east-1',
            'endpoint'        => 'https://example.com',
            'version'         => 'latest',
            'bucket_endpoint' => false,
        ]);

        $this->assertInstanceOf(AwsS3Adapter::class, $return);
    }

    public function testConnectWithCalculateMD5()
    {
        $connector = $this->getMinioConnector();

        $return = $connector->connect([
            'key'           => 'your-key',
            'secret'        => 'your-secret',
            'bucket'        => 'your-bucket',
            'region'        => 'us-east-1',
            'endpoint'      => 'https://example.com',
            'version'       => 'latest',
            'calculate_md5' => true,
        ]);

        $this->assertInstanceOf(AwsS3Adapter::class, $return);
    }

    public function testConnectWithScheme()
    {
        $connector = $this->getMinioConnector();

        $return = $connector->connect([
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'region'   => 'us-east-1',
            'endpoint' => 'https://example.com',
            'version'  => 'latest',
            'scheme'   => 'https',
        ]);

        $this->assertInstanceOf(AwsS3Adapter::class, $return);
    }

    public function testConnectWithEndPoint()
    {
        $connector = $this->getMinioConnector();

        $return = $connector->connect([
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'region'   => 'us-east-1',
            'version'  => 'latest',
            'endpoint' => 'https://example.com',
        ]);

        $this->assertInstanceOf(AwsS3Adapter::class, $return);
    }

    public function testConnectWithEverything()
    {
        $connector = $this->getMinioConnector();

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

        $this->assertInstanceOf(AwsS3Adapter::class, $return);
    }

    public function testConnectWithoutBucket()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minio connector requires bucket configuration.');

        $connector = $this->getMinioConnector();

        $connector->connect([
            'key'     => 'your-key',
            'secret'  => 'your-secret',
            'region'  => 'us-east-1',
            'version' => 'latest',
        ]);
    }

    public function testConnectWithoutKey()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minio connector requires key configuration');
        $connector = $this->getMinioConnector();

        $connector->connect([
            'secret'  => 'your-secret',
            'bucket'  => 'your-bucket',
            'region'  => 'us-east-1',
            'version' => 'latest',
        ]);
    }

    public function testConnectWithoutSecret()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The minio connector requires authentication.');

        $connector = $this->getMinioConnector();

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

        $connector = $this->getMinioConnector();

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

        $connector = $this->getMinioConnector();

        $connector->connect([
            'key'     => 'your-key',
            'secret'  => 'your-secret',
            'bucket'  => 'your-bucket',
            'version' => 'latest',
        ]);
    }

    protected function getMinioConnector()
    {
        return new MinioConnector();
    }
}
