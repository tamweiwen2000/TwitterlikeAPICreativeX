<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Tweet;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function store(Request $request, Tweet $tweet)
    {
        // dd($request);
        // Validate the request
        $request->validate([
            'attachments' => 'required|array|min:1',
            'attachments.*' => 'file|max:2048',
        ]);

        // Upload the attachments
        $attachments = [];
        foreach ($request->file('attachments') as $file) {
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            //Already ran php artisan storage:link command
            $file->storeAs('public/tweets/attachments', $filename);

            $attachment = new Attachment();
            $attachment->tweet_id = $tweet->id;
            $attachment->filename = $filename;
            $attachment->mime_type = $file->getMimeType();
            $attachment->size = $file->getSize();
            $attachment->save();

            $attachments[] = $attachment;
        }

        // Return the attachments
        return response()->json($attachments);
    }
}
