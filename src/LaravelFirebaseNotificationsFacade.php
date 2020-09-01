<?php

namespace Orlyapps\LaravelFirebaseNotifications;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Orlyapps\LaravelFirebaseNotifications\Skeleton\SkeletonClass
 */
class LaravelFirebaseNotificationsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-firebase-notifications';
    }
}
