<?php

namespace Orlyapps\LaravelFirebaseNotifications\Tests;

use Orchestra\Testbench\TestCase;
use Orlyapps\LaravelFirebaseNotifications\LaravelFirebaseNotificationsServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelFirebaseNotificationsServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
