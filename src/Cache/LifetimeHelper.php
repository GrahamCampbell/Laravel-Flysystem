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

namespace GrahamCampbell\Flysystem\Cache;

use Illuminate\Contracts\Cache\Store;
use ReflectionClass;

/**
 * This is the lifetime helper class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class LifetimeHelper
{
    /**
     * Determine the lifetime.
     *
     * @param int $seconds
     *
     * @return int
     */
    public static function computeLifetime(int $seconds)
    {
        return static::isLegacy() ? (int) ceil($seconds / 60.0) : $seconds;
    }

    /**
     * Determine if the cache store is legacy.
     *
     * @return bool
     */
    public static function isLegacy()
    {
        static $legacy;

        if ($legacy === null) {
            $params = (new ReflectionClass(Store::class))->getMethod('put')->getParameters();
            $legacy = $params[2]->getName() === 'minutes';
        }

        return $legacy;
    }
}
