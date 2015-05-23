<?php

/*
 * This file is part of Laravel Flysystem.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\Flysystem\Facades;

use GrahamCampbell\TestBench\Traits\FacadeTestCaseTrait;
use GrahamCampbell\Tests\Flysystem\AbstractTestCase;

/**
 * This is the flysystem facade test class.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
class FlysystemTest extends AbstractTestCase
{
    use FacadeTestCaseTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'flysystem';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return 'GrahamCampbell\Flysystem\Facades\Flysystem';
    }

    /**
     * Get the facade route.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return 'GrahamCampbell\Flysystem\FlysystemManager';
    }
}
