<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Adapters;

use GrahamCampbell\Flysystem\Adapters\AwsS3Connector;
use GrahamCampbell\TestBench\AbstractTestCase;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

/**
 * This is the awss3 connector test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Raul Ruiz <publiux@gmail.com>
 */
class AwsS3ConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect([
            'key'     => 'your-key',
            'secret'  => 'your-secret',
            'bucket'  => 'your-bucket',
            'region'  => 'us-east-1',
            'version' => 'latest',
        ]);

        $this->assertInstanceOf(AwsS3Adapter::class, $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect([
            'key'     => 'your-key',
            'secret'  => 'your-secret',
            'bucket'  => 'your-bucket',
            'region'  => 'us-east-1',
            'version' => 'latest',
            'prefix'  => 'your-prefix',
        ]);

        $this->assertInstanceOf(AwsS3Adapter::class, $return);
    }

    public function testConnectWithBucketEndPoint()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect([
            'key'             => 'your-key',
            'secret'          => 'your-secret',
            'bucket'          => 'your-bucket',
            'region'          => 'us-east-1',
            'version'         => 'latest',
            'bucket_endpoint' => false,
        ]);

        $this->assertInstanceOf(AwsS3Adapter::class, $return);
    }

    public function testConnectWithCalculateMD5()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect([
            'key'           => 'your-key',
            'secret'        => 'your-secret',
            'bucket'        => 'your-bucket',
            'region'        => 'us-east-1',
            'version'       => 'latest',
            'calculate_md5' => true,
        ]);

        $this->assertInstanceOf(AwsS3Adapter::class, $return);
    }

    public function testConnectWithScheme()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect([
            'key'     => 'your-key',
            'secret'  => 'your-secret',
            'bucket'  => 'your-bucket',
            'region'  => 'us-east-1',
            'version' => 'latest',
            'scheme'  => 'https',
        ]);

        $this->assertInstanceOf(AwsS3Adapter::class, $return);
    }

    public function testConnectWithEndPoint()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect([
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'region'   => 'us-east-1',
            'version'  => 'latest',
            'endpoint' => 'your-url',
        ]);

        $this->assertInstanceOf(AwsS3Adapter::class, $return);
    }

    public function testConnectWithEverything()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect([
            'key'             => 'your-key',
            'secret'          => 'your-secret',
            'bucket'          => 'your-bucket',
            'region'          => 'your-region',
            'version'         => 'latest',
            'bucket_endpoint' => false,
            'calculate_md5'   => true,
            'scheme'          => 'https',
            'endpoint'        => 'your-url',
        ]);

        $this->assertInstanceOf(AwsS3Adapter::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutBucket()
    {
        $connector = $this->getAwsS3Connector();

        $connector->connect([
            'key'     => 'your-key',
            'secret'  => 'your-secret',
            'region'  => 'us-east-1',
            'version' => 'latest',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutKey()
    {
        $connector = $this->getAwsS3Connector();

        $connector->connect([
            'secret'  => 'your-secret',
            'bucket'  => 'your-bucket',
            'region'  => 'us-east-1',
            'version' => 'latest',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutSecret()
    {
        $connector = $this->getAwsS3Connector();

        $connector->connect([
            'key'     => 'your-key',
            'bucket'  => 'your-bucket',
            'region'  => 'us-east-1',
            'version' => 'latest',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutRegion()
    {
        $connector = $this->getAwsS3Connector();

        $connector->connect([
            'key'     => 'your-key',
            'secret'  => 'your-secret',
            'bucket'  => 'your-bucket',
            'version' => 'latest',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutVersion()
    {
        $connector = $this->getAwsS3Connector();

        $connector->connect([
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
            'region' => 'us-east-1',
        ]);
    }

    protected function getAwsS3Connector()
    {
        return new AwsS3Connector();
    }
}
