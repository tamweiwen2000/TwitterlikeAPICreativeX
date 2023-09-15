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
    use RefreshDatabase;
    use WithFaker;

    public function testCreateTweetSuccessfully()
    {
        $user = User::factory()->create();
        $tweet = Tweet::factory()->create(['user_id' => $user->id]);
        $tweet = $tweet->toArray();

        $response = $this->actingAs($user)->post("/api/tweets/", $tweet);

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

    public function testCreateTweetUnsuccessfully()
    {
        // Disable form validation middleware
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $tweet = Tweet::factory()
            ->make(['user_id' => $user->id, 'tweet_body' => null])
            ->toArray();

        $response = $this->actingAs($user)->post("/api/tweets/", $tweet);

        $response->assertSessionHasErrors();
    }

    public function testCreateTweetWithAttachmentSuccessfully()
    {

        $user = User::factory()->create();
        $tweet = Tweet::factory()->create(['user_id' => $user->id]);
        $tweet = $tweet->toArray();

        $response = $this->actingAs($user)->post("/api/tweets/", $tweet);

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

        //create many attachments of this tweet
        for ($i = 0; $i < 5; $i++) {
            $attachment = Attachment::factory()->create(['tweet_id' => $tweet_id]);

            $this->assertDatabaseHas('attachments', [
                'tweet_id' => $attachment->tweet_id,
                'filename' => $attachment->filename,
                'mime_type' => $attachment->mime_type,
                'size' => $attachment->size,
            ]);
        }
    }
}
