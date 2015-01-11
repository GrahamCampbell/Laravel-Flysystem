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

use GrahamCampbell\Flysystem\Adapters\AwsS3Connector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the awss3 connector test class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AwsS3ConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect([
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
        ]);

        $this->assertInstanceOf('League\Flysystem\Adapter\AwsS3', $return);
    }

    public function testConnectWithPrefix()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect([
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
            'prefix' => 'your-prefix',
        ]);

        $this->assertInstanceOf('League\Flysystem\Adapter\AwsS3', $return);
    }

    public function testConnectWithRegion()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect([
            'key'    => 'your-key',
            'secret' => 'your-secret',
            'bucket' => 'your-bucket',
            'region' => 'eu-west-1',
        ]);

        $this->assertInstanceOf('League\Flysystem\Adapter\AwsS3', $return);
    }

    public function testConnectWithBaseUrl()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect([
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'base_url' => 'your-url',
        ]);

        $this->assertInstanceOf('League\Flysystem\Adapter\AwsS3', $return);
    }

    public function testConnectWithOptions()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect([
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'options'  => ['foo' => 'bar'],
        ]);

        $this->assertInstanceOf('League\Flysystem\Adapter\AwsS3', $return);
    }

    public function testConnectWithEverything()
    {
        $connector = $this->getAwsS3Connector();

        $return = $connector->connect([
            'key'      => 'your-key',
            'secret'   => 'your-secret',
            'bucket'   => 'your-bucket',
            'region'   => 'eu-west-1',
            'base_url' => 'your-url',
            'options'  => ['foo' => 'bar'],
        ]);

        $this->assertInstanceOf('League\Flysystem\Adapter\AwsS3', $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutBucket()
    {
        $connector = $this->getAwsS3Connector();

        $connector->connect(['key' => 'your-key', 'secret' => 'your-secret']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutKey()
    {
        $connector = $this->getAwsS3Connector();

        $connector->connect(['secret' => 'your-secret', 'bucket' => 'your-bucket']);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConnectWithoutSecret()
    {
        $connector = $this->getAwsS3Connector();

        $connector->connect(['key' => 'your-key', 'bucket' => 'your-bucket']);
    }

    protected function getAwsS3Connector()
    {
        return new AwsS3Connector();
    }
}
