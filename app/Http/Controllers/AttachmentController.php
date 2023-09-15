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

    public function update(Request $request, string $id, object $attachments)
    {
        // Delete the existing attachments
        foreach ($attachments as $attachment) {
            $attachment->delete();
        }

        $newAttachments = [];
        foreach ($request->file('attachments') as $attachment) {
            $filename = uniqid() . '.' . $attachment->getClientOriginalExtension();
            $attachment->storeAs('public/tweets/attachments', $filename);

            $newAttachment = new Attachment();
            $newAttachment->filename = $filename;

            //will add this later on
            // $newAttachment->file_path = $attachment->storeAs('attachments', $attachment->getClientOriginalName()); 

            $newAttachment->tweet_id = $id;
            $newAttachment->mime_type = $attachment->getMimeType();
            $newAttachment->size = $attachment->getSize();
            $newAttachment->save();

            $newAttachments[] = $newAttachment;
        }

        // Return the new attachments
        return response()->json($newAttachments);
    }
}
