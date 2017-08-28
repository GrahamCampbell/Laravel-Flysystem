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

use ChrisWhite\B2\Client;
use GrahamCampbell\Manager\ConnectorInterface;
use InvalidArgumentException;
use Mhetreramesh\Flysystem\BackblazeAdapter;

/**
 * This is the backblaze connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Mattia Trapani <mattia.trapani@gmail.com>
 */
class BackblazeConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \Mhetreramesh\Flysystem\BackblazeAdapter
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
        if (!array_key_exists('accountId', $config)) {
            throw new InvalidArgumentException('The backblaze connector requires accountId.');
        }

        if (!array_key_exists('applicationKey', $config)) {
            throw new InvalidArgumentException('The backblaze connector requires applicationKey.');
        }

        $auth = array_only($config, ['accountId', 'applicationKey']);

        return $auth;
    }

    /**
     * Get the backblaze client.
     *
     * @param string[] $auth
     *
     * @return \ChrisWhite\B2\Client
     */
    protected function getClient(array $auth)
    {
        return new Client($auth['accountId'], $auth['applicationKey']);
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
        if (!array_key_exists('bucket', $config)) {
            throw new InvalidArgumentException('The backblaze connector requires bucket configuration.');
        }

        return array_only($config, ['bucket']);
    }

    /**
     * Get the backblaze adapter.
     *
     * @param \ChrisWhite\B2\Client $client
     * @param string[]              $config
     *
     * @return \Mhetreramesh\Flysystem\BackblazeAdapter
     */
    protected function getAdapter(Client $client, array $config)
    {
        return new BackblazeAdapter($client, $config['bucket']);
    }
}
