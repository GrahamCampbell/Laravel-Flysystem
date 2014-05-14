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

use League\Flysystem\Adapter\Local;
use GrahamCampbell\Flysystem\Interfaces\ConnectorInterface;

/**
 * This is the local connector class.
 *
 * @package    Laravel-Flysystem
 * @author     Graham Campbell
 * @copyright  Copyright 2014 Graham Campbell
 * @license    https://github.com/GrahamCampbell/Laravel-Flysystem/blob/master/LICENSE.md
 * @link       https://github.com/GrahamCampbell/Laravel-Flysystem
 */
class LocalConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param  array  $config
     * @return \League\Flysystem\Adapter\Local
     */
    public function connect(array $config)
    {
        $config = $this->getConfig($config);
        return $this->getAdapter($config);
    }

    /**
     * Get the configuration.
     *
     * @param  array  $config
     * @return array
     */
    protected function getConfig(array $config)
    {
        if (!array_key_exists('path', $config)) {
            throw new \InvalidArgumentException('The local connector requires a path.');
        }

        return array('path' => $config['path']);
    }

    /**
     * Get the local adapter.
     *
     * @param  array  $config
     * @return \League\Flysystem\Adapter\Local
     */
    protected function getAdapter(array $config)
    {
        return new Local($config['path']);
    }
}
