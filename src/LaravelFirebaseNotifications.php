<?php

namespace Orlyapps\LaravelFirebaseNotifications;

use Illuminate\Support\Facades\Route;
use Orlyapps\LaravelFirebaseNotifications\Http\Controllers\PushTokenController;

class LaravelFirebaseNotifications
{
    public static function routes()
    {
        return Route::resource('push-token', PushTokenController::class)->only(['store', 'destroy']);
    }
}
