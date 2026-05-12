<?php

namespace Orlyapps\LaravelFirebaseNotifications\Tests\Feature;

use Orlyapps\LaravelFirebaseNotifications\Models\PushToken;
use Orlyapps\LaravelFirebaseNotifications\Policies\PushTokenPolicy;
use Orlyapps\LaravelFirebaseNotifications\Tests\Stubs\User;
use Orlyapps\LaravelFirebaseNotifications\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PolicyTest extends TestCase
{
    #[Test]
    public function owner_is_allowed_to_delete()
    {
        $user = User::create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'secret',
        ]);
        $token = PushToken::create([
            'user_id' => $user->id,
            'token' => 'mine',
            'type' => 'web',
        ]);

        $this->assertTrue((new PushTokenPolicy)->delete($user, $token));
    }

    #[Test]
    public function non_owner_is_denied()
    {
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => 'secret',
        ]);
        $intruder = User::create([
            'name' => 'Intruder',
            'email' => 'intruder@example.com',
            'password' => 'secret',
        ]);
        $token = PushToken::create([
            'user_id' => $owner->id,
            'token' => 'foreign',
            'type' => 'web',
        ]);

        $this->assertFalse((new PushTokenPolicy)->delete($intruder, $token));
    }
}
