<?php

namespace Orlyapps\LaravelFirebaseNotifications\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Contracts\Auth\Authenticatable;
use Orlyapps\LaravelFirebaseNotifications\Models\PushToken;

class PushTokenPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Authenticatable $user, PushToken $token)
    {
        return $token->user_id === $user->getAuthIdentifier();
    }
}
