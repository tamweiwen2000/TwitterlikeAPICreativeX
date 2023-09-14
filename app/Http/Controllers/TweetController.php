<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Create new tweet
     */
    public function store(Request $request)
    {
        //get current user's id
        $user = Auth::user()->id;

        $fields = $request->validate([
            'tweet_body' => 'required'
        ]);

        return Tweet::create([
            'tweet_body' => $fields['tweet_body'],
            'user_id' => $user
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update tweet
     */
    public function update(Request $request, string $id)
    {
        $tweet = Tweet::find($id);
        $tweet->update([
            'tweet_body' => $request['tweet_body']
        ]);
        return $tweet;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
