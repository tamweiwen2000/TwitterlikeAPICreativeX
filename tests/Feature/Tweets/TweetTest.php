<?php

namespace Tests\Feature;

use App\Models\Attachment;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tweet;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Factories\AttachmentFactory;

class TweetTest extends TestCase
{
    // use RefreshDatabase;
    use WithFaker;

    private User $user;
    private Tweet $tweet;
    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->user = $user;

        $tweet = Tweet::factory()->create(['user_id' => $this->user->id]);
        $this->tweet = $tweet;
    }

    public function testCreateTweetSuccessfully()
    {
        // $tweet = Tweet::factory(1)->create(['user_id' => $this->user]);
        $tweet = $this->tweet->toArray();

        $response = $this->actingAs($this->user)->post("/api/tweets/", $tweet);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(201);

        $response->assertJsonStructure([
            'tweet' => [
                'tweet_body',
                'user_id',
                'updated_at',
                'created_at',
                'id'
            ],
        ]);
    }

    public function testCreateTweetWithAttachmentSuccessfully()
    {

        // $tweet = Tweet::factory(1)->create(['user_id' => $this->user]);
        $tweet = $this->tweet->toArray();

        $response = $this->actingAs($this->user)->post("/api/tweets/", $tweet);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(201);

        $response->assertJsonStructure([
            'tweet' => [
                'tweet_body',
                'user_id',
                'updated_at',
                'created_at',
                'id'
            ],
        ]);

        $tweet_id = $tweet['id'];

        //create many attachments
        Attachment::factory()->create(['tweet_id' => $tweet_id]);
        Attachment::factory()->create(['tweet_id' => $tweet_id]);
        Attachment::factory()->create(['tweet_id' => $tweet_id]);
        Attachment::factory()->create(['tweet_id' => $tweet_id]);
    }
}
