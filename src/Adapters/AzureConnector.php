<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@mineuk.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Flysystem\Adapters;

use GrahamCampbell\Manager\ConnectorInterface;
use InvalidArgumentException;
use League\Flysystem\Azure\Adapter;
use WindowsAzure\Blob\Internal\IBlob;
use WindowsAzure\Common\ServicesBuilder;

/**
 * This is the azure connector class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
class AzureConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\Azure\Adapter
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
        if (!array_key_exists('account-name', $config) || !array_key_exists('api-key', $config)) {
            throw new InvalidArgumentException('The azure connector requires authentication.');
        }

        return array_only($config, ['account-name', 'api-key']);
    }

    /**
     * Get the azure client.
     *
     * @param string[] $auth
     *
     * @return \WindowsAzure\Blob\Internal\IBlob
     */
    protected function getClient(array $auth)
    {
        $endpoint = sprintf('DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s', base64_encode($auth['account-name']), base64_encode($auth['api-key']));

        return ServicesBuilder::getInstance()->createBlobService($endpoint);
    }

    /**
     * Get the configuration.
     *
     * @param string[] $config
     *
     * @return string[]
     */
    protected function getConfig(array $config)
    {
        if (!array_key_exists('container', $config)) {
            throw new InvalidArgumentException('The azure connector requires container configuration.');
        }

        return array_only($config, ['container']);
    }

    /**
     * Get the container adapter.
     *
     * @param \WindowsAzure\Blob\Internal\IBlob $client
     * @param string[]                          $config
     *
     * @return \League\Flysystem\Azure\Adapter
     */
    protected function getAdapter(IBlob $client, array $config)
    {
        return new Adapter($client, $config['container']);
    }
}
