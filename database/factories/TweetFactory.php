<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Tweet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tweet>
 */
class TweetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $userID = User::inRandomOrder()->first()->id;

        return [
            'tweet_body' => fake()->realText(280),
            'user_id' => $userID,
            'attachment_path' => fake()->imageUrl(), // Generate a sample image URL
        ];
    }
}
