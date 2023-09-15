<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\Attachment;
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
            $attachment = $this->attachmentController->store($request, $tweet);
            $attachmentResponse = $attachment->getData();
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
     * Display specific tweet.
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
        dd($request->hasFile('attachments'));
        $tweet = Tweet::find($id);

        $fields = $request->validate([
            'tweet_body' => 'required'
        ]);

        // Get the attachments of the tweet
        $attachments = $tweet->attachments;

        // Update the attachments of the tweet if the $request contains attachments
        if ($request->hasFile('attachments')) {
            // $newAttachments = [];

            // Delete the existing attachments
            foreach ($attachments as $attachment) {
                $attachment->delete();
            }

            foreach ($request->file('attachments') as $attachment) {
                $filename = uniqid() . '.' . $attachment->getClientOriginalExtension();
                $attachment->storeAs('public/tweets/attachments', $filename);

                $newAttachment = new Attachment();
                $newAttachment->filename = $filename;
                // $newAttachment->file_path = $attachment->storeAs('attachments', $attachment->getClientOriginalName());
                $newAttachment->tweet_id = $tweet->id;
                $newAttachment->mime_type = $attachment->getMimeType();
                $newAttachment->size = $attachment->getSize();

                $newAttachment->save();
            }
        }

        $tweet->update([
            'tweet_body' => $fields['tweet_body'],
        ]);

        $response = [
            'tweet' => $tweet,
        ];

        return response($response, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
