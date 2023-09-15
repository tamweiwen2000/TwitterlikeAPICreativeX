<?php

namespace App\Policies;

use App\Models\Tweet;
use App\Models\User;

class TweetPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the given tweet can be updated by the user.
     */
    public function update(User $user, Tweet $tweet): bool
    {
        // dd($user->id);
        return $user->id === $tweet->user_id;
    }
}
