<?php

namespace Orlyapps\LaravelFirebaseNotifications\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PushToken extends Model
{
    use Notifiable;

    protected $fillable = ['user_id', 'type', 'token'];

    public function routeNotificationForFcm()
    {
        return $this->token;
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
