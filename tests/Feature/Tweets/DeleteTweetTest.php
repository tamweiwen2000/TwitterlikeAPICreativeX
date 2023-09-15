<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tweet;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteTweetTest extends TestCase
{
    use RefreshDatabase;

    public function testDeleteTweetSuccessful()
    {
        $user = User::factory()->create();
        $tweet = Tweet::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->deleteJson(route('tweets.destroy', $tweet->id));

        $this->assertNull(Tweet::find($tweet->id));
    }

    public function testDeleteTweetUnauthorized()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $tweet = Tweet::factory()->create([
            'user_id' => $user1->id,
        ]);

        $response = $this->actingAs($user2)->deleteJson(route('tweets.destroy', $tweet->id));

        $response->assertStatus(403);
        $this->assertEquals('This action is unauthorized.', $response->json()['message']);
    }
}
