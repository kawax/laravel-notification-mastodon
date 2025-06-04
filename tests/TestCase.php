<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Application;
use Revolution\Laravel\Notification\Mastodon\MastodonServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Load package service provider.
     *
     * @param  Application  $app
     */
    protected function getPackageProviders($app): array
    {
        return [
        ];
    }

    /**
     * Load package alias.
     *
     * @param  Application  $app
     */
    protected function getPackageAliases($app): array
    {
        return [
            //
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('services.mastodon',
            [
                'domain' => 'mastodon.social',
                'token' => 'test-token',
            ],
        );
    }
}
