<?php

namespace Orlyapps\LaravelFirebaseNotifications\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Orlyapps\LaravelFirebaseNotifications\Models\PushToken;

class PushTokenPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, PushToken $token)
    {
        return $token->user_id === $user->id;
    }
}
