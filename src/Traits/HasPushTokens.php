<?php

namespace Orlyapps\LaravelFirebaseNotifications\Traits;

use Orlyapps\LaravelFirebaseNotifications\Models\PushToken;

/**
 *
 */
trait HasPushTokens
{
    public function pushTokens()
    {
        return $this->hasMany(PushToken::class);
    }
}
