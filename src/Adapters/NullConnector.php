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

use GrahamCampbell\Manager\ConnectorInterface;
use League\Flysystem\Adapter\NullAdapter;

/**
 * This is the null connector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class NullConnector implements ConnectorInterface
{
    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \League\Flysystem\Adapter\NullAdapter
     */
    public function connect(array $config)
    {
        return $this->getAdapter();
    }

    /**
     * Get the null adapter.
     *
     * @return \League\Flysystem\Adapter\NullAdapter
     */
    protected function getAdapter()
    {
        return new NullAdapter();
    }
}
