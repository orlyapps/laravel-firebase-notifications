<?php

namespace Orlyapps\LaravelFirebaseNotifications\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Orlyapps\LaravelFirebaseNotifications\LaravelFirebaseNotificationsServiceProvider;
use Orlyapps\LaravelFirebaseNotifications\Tests\Stubs\User;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [LaravelFirebaseNotificationsServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('auth.providers.users.model', User::class);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadLaravelMigrations(['--database' => 'testing']);
        $this->artisan('migrate', ['--database' => 'testing'])->run();
    }
}
