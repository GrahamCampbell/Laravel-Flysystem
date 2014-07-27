<?php

/**
 * This file is part of Laravel Flysystem by Graham Campbell.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace GrahamCampbell\Flysystem\Adapters;

use Aws\S3\S3Client;
use GrahamCampbell\Manager\ConnectorInterface;
use League\Flysystem\Adapter\AwsS3;

/**
 * This is the awss3 connector class.
 *
 * @author    Graham Campbell <graham@mineuk.com>
 * @copyright 2014 Graham Campbell
 * @license   <https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md> Apache 2.0
 */
class AwsS3Connector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\Adapter\AwsS3
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
            throw new \InvalidArgumentException('The awss3 connector requires authentication.');
        }

        if (array_key_exists('region', $config)) {
            return array_only($config, array('key', 'secret', 'region'));
        } else {
            return array_only($config, array('key', 'secret'));
        }
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
        return S3Client::factory($auth);
    }

    /**
     * Get the configuration.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return string[]
     */
    protected function getConfig(array $config)
    {
        if (!array_key_exists('prefix', $config)) {
            $config['prefix'] = null;
        }

        if (!array_key_exists('bucket', $config)) {
            throw new \InvalidArgumentException('The awss3 connector requires a bucket.');
        }

        return array_only($config, array('bucket', 'prefix'));
    }

    /**
     * Get the awss3 adapter.
     *
     * @param \Aws\S3\S3Client $client
     * @param string[]         $config
     *
     * @return \League\Flysystem\Adapter\AwsS3
     */
    protected function getAdapter(S3Client $client, array $config)
    {
        return new AwsS3($client, $config['bucket'], $config['prefix']);
    }
}
