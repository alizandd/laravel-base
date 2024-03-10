<?php

namespace App\Http\Controllers\Api\V1\Wall;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /*public function toggleLike(Request $request)
    {
        $request->validate([
            'likeable_type' => 'required|string|in:Post,Comment',
            'likeable_id' => 'required|integer',
        ]);

        $type = 'App\Models\\' . $request->likeable_type;
        $likeable = $type::find($request->likeable_id);

        if (!$likeable) {
            return response()->json(['message' => 'Content not found'], 404);
        }

        $like = $likeable->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            // If already liked, unlike it
            $like->delete();
            return response()->json(['message' => 'Successfully unliked']);
        } else {
            // If not liked, like it
            $likeable->likes()->create(['user_id' => Auth::id()]);
            return response()->json(['message' => 'Successfully liked']);
        }
    }*/
}
