<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AttachmentController;

class TweetController extends Controller
{
    private $attachmentController;

    public function __construct(AttachmentController $otherController)
    {
        $this->attachmentController = $otherController;
    }

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

        $tweet = Tweet::create([
            'tweet_body' => $fields['tweet_body'],
            'user_id' => $user,
        ]);

        //Check if there are attachments on tweet
        if ($request['attachments']) {
            $attachment = $this->attachmentController->store($request, $tweet->id);
            $attachmentResponse = $attachment->original;
        } else {
            $attachmentResponse = null;
        }

        $response = [
            'tweet' => $tweet,
            'attachments' => $attachmentResponse
        ];

        return response($response, 201);
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
