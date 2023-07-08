<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTweetRequest;
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
        // Process the file uploads
        if ($request->hasFile('files')) {
            // foreach ($request->file('files') as $file) {}
        }

        $tweet = Tweet::create([
            'user_id' => $request->user_id,
            'body' => $request->body,
            'file_attachment' => null,
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
    //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tweet $tweet)
    {
        $tweet->update([
            'body' => $request->body
        ]);

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
