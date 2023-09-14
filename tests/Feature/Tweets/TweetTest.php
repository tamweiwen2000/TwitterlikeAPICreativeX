<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tweet;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TweetTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private User $user;
    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory(1)->create()->first();
        $this->user = $user;
    }

    public function testCreateTweetSuccessfully()
    {
        $this->assertDatabaseCount('tweets', 0);

        $tweet = Tweet::factory(1)->make();
        $tweet = $tweet->toArray();

        $response = $this->actingAs($this->user)->post("/api/tweets/", $tweet[0]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(201);

        $response->assertJsonStructure([
            'tweet' => [
                'tweet_body',
                'user_id',
                'attachment_path',
                'updated_at',
                'created_at',
                'id'
            ],
        ]);

        $this->assertDatabaseCount('tweets', 1);
    }
}
