<?php

namespace Orlyapps\LaravelFirebaseNotifications;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Orlyapps\LaravelFirebaseNotifications\Models\PushToken;
use Orlyapps\LaravelFirebaseNotifications\Policies\PushTokenPolicy;

class LaravelFirebaseNotificationsServiceProvider extends ServiceProvider
{
    protected $policies = [
        PushToken::class => PushTokenPolicy::class,
    ];

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('firebase-notifications.php'),
            ], 'config');
        }

        Route::bind('push_token', function ($value) {
            return PushToken::where('token', $value)->first() ?? abort(404);
        });

        $this->registerPolicies();
    }

    public function registerPolicies()
    {
        foreach ($this->policies as $key => $value) {
            Gate::policy($key, $value);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-firebase-notifications');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-firebase-notifications', function () {
            return new LaravelFirebaseNotifications;
        });
    }
}
