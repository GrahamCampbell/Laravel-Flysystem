<?php

/*
 * This file is part of Laravel Flysystem. It was developed by Raul Ruiz.
 *
 * (c) Raul Ruiz <publiux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Adapters;

use GrahamCampbell\Flysystem\Adapters\AwsS3V3Connector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the awss3v3 connector test class.
 *
 * @author Raul Ruiz <publiux@gmail.com>
 */
class AwsS3V3ConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getAwsS3V3Connector();

        $return = $connector->connect([
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
            'region' => 'us-east-1',
            'version' => '2006-03-01',
        ]);

        $this->assertInstanceOf('League\Flysystem\AwsS3v3\AwsS3Adapter', $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getAwsS3V3Connector();

        $return = $connector->connect([
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
            'region' => 'us-west-2',
            'version' => '2006-03-01',
            'prefix' => 'your-prefix',
        ]);

        $this->assertInstanceOf('League\Flysystem\AwsS3v3\AwsS3Adapter', $return);
    }

    public function testConnectWithBaseUrl()
    {
        $connector = $this->getAwsS3V3Connector();

        $return = $connector->connect([
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'region' => 'us-west-2',
            'version' => 'latest',
            'base_url' => 'your-url',
        ]);

        $this->assertInstanceOf('League\Flysystem\AwsS3v3\AwsS3Adapter', $return);
    }

    public function testConnectWithOptions()
    {
        $connector = $this->getAwsS3V3Connector();

        $return = $connector->connect([
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'region' => 'us-west-2',
            'version' => 'latest',
            'options'  => ['foo' => 'bar'],
        ]);

        $this->assertInstanceOf('League\Flysystem\AwsS3v3\AwsS3Adapter', $return);
    }

    public function testConnectWithEverything()
    {
        $connector = $this->getAwsS3V3Connector();

        $return = $connector->connect([
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'region' => 'us-west-2',
            'version' => 'latest',
            'base_url' => 'your-url',
            'options'  => ['foo' => 'bar'],
        ]);

        $this->assertInstanceOf('League\Flysystem\AwsS3v3\AwsS3Adapter', $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutBucket()
    {
        $connector = $this->getAwsS3V3Connector();

        $connector->connect([
            'key' => 'your-key',
            'secret' => 'your-secret',
            'region' => 'us-west-2',
            'version' => 'latest',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutKey()
    {
        $connector = $this->getAwsS3V3Connector();

        $connector->connect([
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
            'region' => 'us-west-2',
            'version' => 'latest',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutSecret()
    {
        $connector = $this->getAwsS3V3Connector();

        $connector->connect([
            'key' => 'your-key',
            'bucket' => 'your-bucket',
            'region' => 'us-west-2',
            'version' => 'latest',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutRegion()
    {
        $connector = $this->getAwsS3V3Connector();

        $connector->connect([
            'key' => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
            'version' => 'latest',
        ]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutVersion()
    {
        $connector = $this->getAwsS3V3Connector();

        $connector->connect([
            'key' => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
            'region' => 'us-west-2',
        ]);
    }

    protected function getAwsS3V3Connector()
    {
        return new AwsS3V3Connector();
    }
}