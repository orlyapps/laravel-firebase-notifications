<?php

namespace Orlyapps\LaravelFirebaseNotifications\Tests\Feature;

use Illuminate\Support\Facades\Route;
use Orlyapps\LaravelFirebaseNotifications\LaravelFirebaseNotifications;
use Orlyapps\LaravelFirebaseNotifications\Models\PushToken;
use Orlyapps\LaravelFirebaseNotifications\Tests\Stubs\User;
use Orlyapps\LaravelFirebaseNotifications\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class RoutesTest extends TestCase
{
    protected function defineRoutes($router)
    {
        $router->middleware('api')->group(function () {
            LaravelFirebaseNotifications::routes();
        });
    }

    #[Test]
    public function it_registers_store_and_destroy_routes()
    {
        $names = collect(Route::getRoutes())
            ->map(fn ($route) => $route->getName())
            ->filter()
            ->values()
            ->all();

        $this->assertContains('push-token.store', $names);
        $this->assertContains('push-token.destroy', $names);
    }

    #[Test]
    public function authenticated_user_can_store_a_push_token()
    {
        $user = $this->makeUser('alice@example.com');

        $response = $this->actingAs($user)->postJson('/push-token', [
            'token' => 'device-abc',
            'type' => 'web',
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas('push_tokens', [
            'token' => 'device-abc',
            'user_id' => $user->id,
            'type' => 'web',
        ]);
    }

    #[Test]
    public function storing_an_existing_token_for_a_different_user_replaces_it()
    {
        $alice = $this->makeUser('alice@example.com');
        $bob = $this->makeUser('bob@example.com');

        PushToken::create([
            'user_id' => $alice->id,
            'token' => 'shared',
            'type' => 'web',
        ]);

        $this->actingAs($bob)
            ->postJson('/push-token', ['token' => 'shared', 'type' => 'web'])
            ->assertSuccessful();

        $this->assertDatabaseMissing('push_tokens', [
            'user_id' => $alice->id,
            'token' => 'shared',
        ]);
        $this->assertDatabaseHas('push_tokens', [
            'user_id' => $bob->id,
            'token' => 'shared',
        ]);
    }

    #[Test]
    public function owner_can_destroy_their_push_token()
    {
        $user = $this->makeUser('alice@example.com');
        PushToken::create([
            'user_id' => $user->id,
            'token' => 'mine',
            'type' => 'web',
        ]);

        $this->actingAs($user)
            ->deleteJson('/push-token/mine')
            ->assertSuccessful();

        $this->assertDatabaseMissing('push_tokens', ['token' => 'mine']);
    }

    #[Test]
    public function non_owner_cannot_destroy_someone_elses_push_token()
    {
        $owner = $this->makeUser('alice@example.com');
        $intruder = $this->makeUser('bob@example.com');
        PushToken::create([
            'user_id' => $owner->id,
            'token' => 'foreign',
            'type' => 'web',
        ]);

        $this->actingAs($intruder)
            ->deleteJson('/push-token/foreign')
            ->assertForbidden();

        $this->assertDatabaseHas('push_tokens', [
            'token' => 'foreign',
            'user_id' => $owner->id,
        ]);
    }

    private function makeUser(string $email): User
    {
        return User::create([
            'name' => explode('@', $email)[0],
            'email' => $email,
            'password' => 'secret',
        ]);
    }
}
