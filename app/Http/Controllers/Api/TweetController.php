<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTweetRequest;
use App\Http\Requests\UpdateTweetRequest;
use App\Http\Resources\TweetResource;
use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TweetResource::collection(Tweet::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTweetRequest $request)
    {
        $fas = [];
        // Process the file uploads
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            if (is_array($files)) {
                // Handle multiple file uploads
                foreach ($files as $file) {
                    $path = $file->store('uploads', 'public');
                    $filename = $file->getClientOriginalName();
                    $fas[] = $filename;
                }
            } else {
                // Handle single file upload
                $path = $files->store('uploads', 'public');
                $filename = $files->getClientOriginalName();
                $fas[] = $filename;
            }
        }

        $tweet = Tweet::create([
            'user_id' => $request->user_id,
            'body' => $request->body,
            'file_attachment' => count($fas) ? json_encode($fas) : null,
        ]);

        if($tweet) {
            return response("Tweet posted!");
        } else {
            return response("Unable to complete the task", 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Tweet $tweet)
    {
        return new TweetResource($tweet);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTweetRequest $request, Tweet $tweet)
    {
        $updatedData = [
            'body' => $request->body
        ];

        $fas = [];
        // Process the file uploads
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            if (is_array($files)) {
                // Handle multiple file uploads
                foreach ($files as $file) {
                    $path = $file->store('uploads', 'public');
                    $filename = $file->getClientOriginalName();
                    $fas[] = $filename;
                }
            } else {
                // Handle single file upload
                $path = $files->store('uploads', 'public');
                $filename = $files->getClientOriginalName();
                $fas[] = $filename;
            }

            $updatedData['file_attachment'] = json_encode($fas);
        }


        $tweet->update($updatedData);

        return new TweetResource($tweet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tweet $tweet)
    {
        return $tweet->delete();
    }
}
