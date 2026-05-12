<?php

namespace Orlyapps\LaravelFirebaseNotifications\Tests\Feature;

use Kreait\Firebase\Exception\Messaging\InvalidMessage;
use Kreait\Firebase\Exception\Messaging\NotFound;
use Mockery;
use Orlyapps\LaravelFirebaseNotifications\Channels\PushChannel;
use Orlyapps\LaravelFirebaseNotifications\Notifications\TextNotification;
use Orlyapps\LaravelFirebaseNotifications\Tests\Stubs\User;
use Orlyapps\LaravelFirebaseNotifications\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PushChannelTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function text_notification_builds_expected_payload()
    {
        $notification = new TextNotification('Hi', 'Body', 'https://example.com');
        $notifiable = new \stdClass();

        $this->assertSame([PushChannel::class], $notification->via($notifiable));
        $this->assertSame([
            'title' => 'Hi',
            'body' => 'Body',
            'url' => 'https://example.com',
            'data' => [],
        ], $notification->toPush($notifiable));
    }

    #[Test]
    public function channel_invokes_push_once_per_token()
    {
        $user = $this->makeUser('alice@example.com');
        $user->pushTokens()->create(['token' => 't1', 'type' => 'web']);
        $user->pushTokens()->create(['token' => 't2', 'type' => 'ios']);

        $channel = Mockery::mock(PushChannel::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $captured = [];
        $channel->shouldReceive('push')
            ->twice()
            ->withArgs(function ($title, $body, $url, $deviceToken, $data) use (&$captured) {
                $captured[] = $deviceToken;
                return $title === 'Hi'
                    && $body === 'Body'
                    && $url === 'https://example.com'
                    && $data === [];
            });

        $channel->send($user->fresh(), new TextNotification('Hi', 'Body', 'https://example.com'));

        sort($captured);
        $this->assertSame(['t1', 't2'], $captured);
    }

    #[Test]
    public function channel_deletes_token_when_firebase_reports_not_found()
    {
        $user = $this->makeUser('alice@example.com');
        $user->pushTokens()->create(['token' => 'stale', 'type' => 'web']);

        $channel = Mockery::mock(PushChannel::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $channel->shouldReceive('push')
            ->once()
            ->andThrow(NotFound::becauseTokenNotFound('stale'));

        $channel->send($user->fresh(), new TextNotification('Hi', 'Body', 'https://example.com'));

        $this->assertDatabaseMissing('push_tokens', ['token' => 'stale']);
    }

    #[Test]
    public function channel_deletes_token_on_invalid_message_exception()
    {
        $user = $this->makeUser('alice@example.com');
        $user->pushTokens()->create(['token' => 'bad', 'type' => 'web']);

        $channel = Mockery::mock(PushChannel::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $channel->shouldReceive('push')
            ->once()
            ->andThrow(new InvalidMessage('invalid'));

        $channel->send($user->fresh(), new TextNotification('Hi', 'Body', 'https://example.com'));

        $this->assertDatabaseMissing('push_tokens', ['token' => 'bad']);
    }

    #[Test]
    public function channel_keeps_token_when_a_generic_throwable_is_thrown()
    {
        $user = $this->makeUser('alice@example.com');
        $user->pushTokens()->create(['token' => 'keep', 'type' => 'web']);

        $channel = Mockery::mock(PushChannel::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $channel->shouldReceive('push')
            ->once()
            ->andThrow(new \RuntimeException('transient'));

        $channel->send($user->fresh(), new TextNotification('Hi', 'Body', 'https://example.com'));

        $this->assertDatabaseHas('push_tokens', ['token' => 'keep']);
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
