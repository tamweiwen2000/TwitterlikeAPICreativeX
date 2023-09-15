<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tweet;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateTweetTest extends TestCase
{

    use RefreshDatabase;

    public function testUpdateTweetSuccess()
    {
        $user = User::factory()->create();
        $tweet = Tweet::factory()->create([
            'user_id' => $user->id,
        ]);
        $tweet = $tweet->toArray();

        $response = $this->actingAs($user)
            ->putJson(
                route('tweets.update', $tweet['id']),
                ['tweet_body' => 'This is a new tweet body.']
            );

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

    public function testUpdateTweetPermissionDenied()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $tweet = Tweet::factory()->create([
            'user_id' => $user1->id,
        ]);
        $tweet = $tweet->toArray();

        $response = $this->actingAs($user2)
            ->putJson(
                route('tweets.update', $tweet['id']),
                ['tweet_body' => 'This is a new tweet body.']
            );

        $response->assertStatus(403);
        $this->assertEquals('You do not have permission to update this tweet.', $response->json()['error']);
    }

    //todo: make tests on update tweet with attachments 
}
