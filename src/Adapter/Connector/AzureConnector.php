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

namespace GrahamCampbell\Flysystem\Adapter\Connector;

use GrahamCampbell\Manager\ConnectorInterface;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;

/**
 * This is the azure connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
final class AzureConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return \League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter
     */
    public function connect(array $config)
    {
        $auth = self::getAuth($config);
        $client = self::getClient($auth);
        $config = self::getConfig($config);

        return self::getAdapter($client, $config);
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
    private static function getAuth(array $config)
    {
        if (!array_key_exists('account-name', $config) || !array_key_exists('api-key', $config)) {
            throw new InvalidArgumentException('The azure connector requires authentication.');
        }

        return Arr::only($config, ['account-name', 'api-key']);
    }

    /**
     * Get the azure client.
     *
     * @param string[] $auth
     *
     * @return \MicrosoftAzure\Storage\Blob\BlobRestProxy
     */
    private static function getClient(array $auth)
    {
        $endpoint = sprintf('DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s', $auth['account-name'], $auth['api-key']);

        return BlobRestProxy::createBlobService($endpoint);
    }

    /**
     * Get the configuration.
     *
     * @param string[] $config
     *
     * @return string[]
     */
    private static function getConfig(array $config)
    {
        if (!array_key_exists('container', $config)) {
            throw new InvalidArgumentException('The azure connector requires container configuration.');
        }

        return Arr::only($config, ['container']);
    }

    /**
     * Get the container adapter.
     *
     * @param \MicrosoftAzure\Storage\Blob\BlobRestProxy $client
     * @param string[]                                   $config
     *
     * @return \League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter
     */
    private static function getAdapter(BlobRestProxy $client, array $config)
    {
        return new AzureBlobStorageAdapter($client, $config['container']);
    }
}
