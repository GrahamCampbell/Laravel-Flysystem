<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@alt-three.com>
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
 * This is the awss3 connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Raul Ruiz <publiux@gmail.com>
 */
class AwsS3Connector implements ConnectorInterface
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

        if (!array_key_exists('version', $config)) {
            throw new InvalidArgumentException('The awss3 connector requires version configuration.');
        }

        if (!array_key_exists('region', $config)) {
            throw new InvalidArgumentException('The awss3 connector requires region configuration.');
        }

        $auth = [
            'region'      => $config['region'],
            'version'     => $config['version'],
            'credentials' => array_only($config, ['key', 'secret']),
        ];

        if (array_key_exists('bucket_endpoint', $config)) {
            $auth['bucket_endpoint'] = $config['bucket_endpoint'];
        }

        if (array_key_exists('calculate_md5', $config)) {
            $auth['calculate_md5'] = $config['calculate_md5'];
        }

        if (array_key_exists('scheme', $config)) {
            $auth['scheme'] = $config['scheme'];
        }

        if (array_key_exists('endpoint', $config)) {
            $auth['endpoint'] = $config['endpoint'];
        }

        return $auth;
    }

    /**
     * Get the awss3 client.
     *
     * @param string[] $auth
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
            throw new InvalidArgumentException('The awss3 connector requires bucket configuration.');
        }

        return array_only($config, ['bucket', 'prefix']);
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
