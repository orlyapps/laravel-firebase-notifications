<?php

namespace Orlyapps\LaravelFirebaseNotifications\Tests\Feature;

use Illuminate\Support\Facades\Schema;
use Orlyapps\LaravelFirebaseNotifications\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MigrationTest extends TestCase
{
    #[Test]
    public function it_creates_the_push_tokens_table()
    {
        $this->assertTrue(Schema::hasTable('push_tokens'));
        $this->assertTrue(Schema::hasColumns('push_tokens', [
            'id', 'user_id', 'token', 'type', 'created_at', 'updated_at',
        ]));
    }
}
