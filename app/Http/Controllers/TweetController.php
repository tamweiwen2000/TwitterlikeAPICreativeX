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

        // $attachment_path = $request['attachment_path'];

        // $attachment_path = $request->file('attachment_path')->getClientOriginalName();

        // dd($attachment_path);

        // $attachment = $request->file('attachment_path')->store('public/tweets/attachments');


        // if ($attachment_path != null) {
        //     // $uploadResponse = $this->uploadAttachment($attachment_path);

        //     // if ($uploadResponse->status() == 500) return $uploadResponse;
        //     // $attachment_path = $uploadResponse->getData()->attachment_path;

        //     $imgName = $request->file('attachment_path')->getClientOriginalName();
        //     $attachment_path = $request->file('attachment_path')->storeAs('public/tweets/attachments', $imgName);
        // }

        // MULTIPLE FILES
        // $files = [];
        // if ($request->file('attachment_path')) {
        //     foreach ($request->file('attachment_path') as $key => $file) {
        //         $fileName = time() . rand(1, 99) . '.' . $file->extension();
        //         $file->move(public_path('uploads'), $fileName);
        //         $files[]['name'] = $fileName;
        //     }
        // }

        $tweet = Tweet::create([
            'tweet_body' => $fields['tweet_body'],
            'user_id' => $user,
        ]);

        if ($request['attachments']) {
            $attachment = $this->attachmentController->store($request, $tweet->id);
        } else {
            $attachment = null;
        }

        $response = [
            'tweet' => $tweet,
            'attachments' => $attachment->original
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

    // public function uploadAttachment($attachment): JsonResponse
    // {
    //     if ($attachment == null) {
    //         return response()->json(['error' => 'Attachment not provided'], 400);
    //     }
    //     try {

    //         Storage::disk('public')->put('tweets/attachments', $attachment);
    //         $attachmentPath = 'tweets/attachments/' . $attachment->hashName(); // Use the appropriate path structure

    //         // NOTE: The php artisan storage:link command has been run already,
    //         // so the Storage::url() method will return the publicly available correct URL
    //         // docs: https://laravel.com/docs/10.x/filesystem#the-public-disk
    //         $attachmentUrl = Storage::url($attachmentPath);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'An error occurred while saving the image'], 500);
    //     }
    //     return response()->json(['message' => 'Attachment uploaded successfully', 'attachment_path' => $attachmentUrl], 200);
    // }
}
