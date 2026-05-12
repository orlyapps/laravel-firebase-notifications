<?php

namespace Orlyapps\LaravelFirebaseNotifications\Tests\Feature;

use Orlyapps\LaravelFirebaseNotifications\Models\PushToken;
use Orlyapps\LaravelFirebaseNotifications\Tests\Stubs\User;
use Orlyapps\LaravelFirebaseNotifications\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PushTokenModelTest extends TestCase
{
    #[Test]
    public function it_persists_a_push_token()
    {
        $user = User::create([
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'password' => 'secret',
        ]);

        $token = PushToken::create([
            'user_id' => $user->id,
            'token' => 'device-abc',
            'type' => 'ios',
        ]);

        $this->assertDatabaseHas('push_tokens', [
            'token' => 'device-abc',
            'user_id' => $user->id,
            'type' => 'ios',
        ]);
        $this->assertSame('device-abc', $token->routeNotificationForFcm());
    }

    #[Test]
    public function user_relation_resolves_via_configured_auth_model()
    {
        $user = User::create([
            'name' => 'Bob',
            'email' => 'bob@example.com',
            'password' => 'secret',
        ]);
        $token = PushToken::create([
            'user_id' => $user->id,
            'token' => 'tok',
            'type' => 'web',
        ]);

        $this->assertInstanceOf(User::class, $token->user);
        $this->assertSame($user->id, $token->user->id);
    }

    #[Test]
    public function has_push_tokens_trait_returns_user_tokens()
    {
        $user = User::create([
            'name' => 'Carol',
            'email' => 'carol@example.com',
            'password' => 'secret',
        ]);
        $user->pushTokens()->create(['token' => 't1', 'type' => 'web']);
        $user->pushTokens()->create(['token' => 't2', 'type' => 'ios']);

        $this->assertCount(2, $user->fresh()->pushTokens);
    }
}
