<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function store(Request $request, int $tweet_id)
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
            $attachment->tweet_id = $tweet_id;
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
