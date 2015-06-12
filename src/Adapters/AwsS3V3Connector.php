<?php

/*
 * This file is part of Laravel Flysystem. This module was developed by Raul Ruiz.
 *
 * (c) Raul Ruiz <publiux@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Flysystem\Adapters;

use Aws\S3\S3Client;
use GrahamCampbell\Manager\ConnectorInterface;
use InvalidArgumentException;
use League\Flysystem\AwsS3v3\AwsS3Adapter;

/**
 * This is the awss3v3 connector class.
 *
 * @author Raul Ruiz <publiux@gmail.com>
 */
class AwsS3V3Connector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\AwsS3v3\AwsS3Adapter
     */
    public function connect(array $config)
    {
        $auth = $this->getAuth($config);
        $client = $this->getClient($auth);
        $config = $this->getConfig($config);

        return $this->getAdapter($client, $config);
    }

    /**
     * Get the authentication data.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return string[]
     */
    protected function getAuth(array $config)
    {
        if (!array_key_exists('key', $config) || !array_key_exists('secret', $config)) {
            throw new InvalidArgumentException('The awss3 connector requires authentication.');
        }

        if (!array_key_exists('region', $config)) {
            throw new InvalidArgumentException('The awss3v3 connector requires a region to be specified.');
        }

        if (!array_key_exists('version', $config)) {
            throw new InvalidArgumentException('The awss3v3 connector requires an API version to be specified.');
        }

        if (array_key_exists('base_url', $config)) {
            $config = array_only($config, ['key', 'secret', 'region', 'version', 'base_url']);
        }

        $config = array_only($config, ['key', 'secret', 'region', 'version']);

        return array(
            'version'     => $config['version'],
            'region'      => $config['region'],
            'credentials'   => [
                'key' => $config['key'],
                'secret' => $config['secret'],
            ],
        );
    }

    /**
     * Get the awss3 client.
     *
     * @param array $auth
     *
     * @return \Aws\S3\S3Client
     */
    protected function getClient(array $auth)
    {
        return new S3Client($auth);
    }

    /**
     * Get the configuration.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return array
     */
    protected function getConfig(array $config)
    {
        if (!array_key_exists('prefix', $config)) {
            $config['prefix'] = null;
        }

        if (!array_key_exists('bucket', $config)) {
            throw new InvalidArgumentException('The awss3 connector requires a bucket.');
        }

        if (!array_key_exists('options', $config)) {
            $config['options'] = [];
        }

        return array_only($config, ['bucket', 'prefix', 'options']);
    }

    /**
     * Get the awss3 adapter.
     *
     * @param \Aws\S3\S3Client $client
     * @param string[]         $config
     *
     * @return \League\Flysystem\AwsS3v3\AwsS3Adapter
     */
    protected function getAdapter(S3Client $client, array $config)
    {
        return new AwsS3Adapter($client, $config['bucket'], $config['prefix']);
    }
}