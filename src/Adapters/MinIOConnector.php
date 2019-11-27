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

namespace GrahamCampbell\Flysystem\Adapters;

use Aws\S3\S3Client;
use GrahamCampbell\Flysystem\Facades\Flysystem;
use GrahamCampbell\Manager\ConnectorInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\MinIO\MinIOAdapter;
use League\Flysystem\Filesystem;

/**
 * This is the minio connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Raul Ruiz <publiux@gmail.com>
 */
class MinIOConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\MinIO\MinIOAdapter
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
     * @return string[]
     * @throws \InvalidArgumentException
     *
     */
    protected function getAuth(array $config)
    {
        if (!array_key_exists('version', $config)) {
            throw new InvalidArgumentException('The minio connector requires version configuration.');
        }

        if (!array_key_exists('region', $config)) {
            throw new InvalidArgumentException('The minio connector requires region configuration.');
        }

        $auth = [
            'region' => $config['region'],
            'version' => $config['version'],
        ];

        if (isset($config['key'])) {
            if (!array_key_exists('secret', $config)) {
                throw new InvalidArgumentException('The minio connector requires authentication.');
            }
            $auth['credentials'] = Arr::only($config, ['key', 'secret']);
        }

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
     * Get the minio client.
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
     * @return array
     * @throws \InvalidArgumentException
     *
     */
    protected function getConfig(array $config)
    {
        if (!array_key_exists('prefix', $config)) {
            $config['prefix'] = null;
        }

        if (!array_key_exists('bucket', $config)) {
            throw new InvalidArgumentException('The minio connector requires bucket configuration.');
        }

        return Arr::only($config, ['key','secret','region','endpoint','bucket']);
    }

    /**
     * Get the minio adapter.
     *
     * @param \Aws\S3\S3Client $client
     * @param string[] $config
     *
     * @return \League\Flysystem\AwsS3v3\AwsS3Adapter
     */
    protected function getAdapter(S3Client $client, array $config)
    {
        $client = new S3Client([
            'credentials' => [
                'key' => $config["key"],
                'secret' => $config["secret"]
            ],
            'region' => $config["region"],
            'version' => "latest",
            'bucket_endpoint' => false,
            'use_path_style_endpoint' => true,
            'endpoint' => $config["endpoint"],
        ]);
        $options = [
            'override_visibility_on_copy' => true
        ];
        return new AwsS3Adapter($client, $config["bucket"], '', $options);
    }
}