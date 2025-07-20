<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    public function toggleLike($postId, Request $request)
    {
        $user = $request->user();
        $like = Like::where('user_id', $user->id)->where('post_id', $postId)->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Unliked']);
        } else {
            Like::create([
                'user_id' => $user->id,
                'post_id' => $postId,
            ]);
            return response()->json(['message' => 'Liked']);
        }
    }

    public function showLikes($postId, Request $request)
    {
        $post = Post::findOrFail($postId);
        $user = $request->user();

        if ($post->user_id !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $likes = $post->likes()->with('user:id,username')->get();

        return response()->json([
            'count' => $likes->count(),
            'users' => $likes->pluck('user.username'),
        ]);
    }
}
