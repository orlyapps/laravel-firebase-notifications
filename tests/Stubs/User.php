<?php

namespace Orlyapps\LaravelFirebaseNotifications\Tests\Stubs;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Orlyapps\LaravelFirebaseNotifications\Traits\HasPushTokens;

class User extends Authenticatable
{
    use HasPushTokens;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'password'];
}
