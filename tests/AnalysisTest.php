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

namespace GrahamCampbell\Tests\Flysystem;

use GrahamCampbell\Analyzer\AnalysisTrait;
use Laravel\Lumen\Application;
use MongoClient;
use MongoConnectionException:;
use PHPUnit\Framework\TestCase;

/**
 * This is the analysis test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class AnalysisTest extends TestCase
{
    use AnalysisTrait;

    /**
     * Get the code paths to analyze.
     *
     * @return string[]
     */
    protected function getPaths()
    {
        return [
            realpath(__DIR__.'/../config'),
            realpath(__DIR__.'/../src'),
            realpath(__DIR__),
        ];
    }

    /**
     * Get the classes to ignore not existing.
     *
     * @return string[]
     */
    protected function getIgnored()
    {
        return [Application::class, MongoClient::class, MongoConnectionException::class];
    }
}
