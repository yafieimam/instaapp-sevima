<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image'          => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'caption'        => 'nullable|string',
            'allow_comment'  => 'required|boolean',
        ]);

        $imagePath = $request->file('image')->store('posts', 'public');

        $post = Post::create([
            'user_id'       => $request->user()->id,
            'image_path'    => $imagePath,
            'caption'       => $request->caption,
            'allow_comment' => $request->allow_comment,
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post'    => $post,
        ]);
    }

    public function index()
    {
        return Post::with('user')->latest()->get();
    }

    public function postsByUser($id)
    {
        $user = User::findOrFail($id);

        $posts = Post::with(['user', 'likes', 'comments.user'])
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'user' => $user,
            'posts' => $posts
        ]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'caption' => 'required|string|max:255',
            'allow_comment' => 'boolean',
        ]);

        $post->update([
            'caption' => $request->caption,
            'allow_comment' => $request->has('allow_comment'),
        ]);

        return response()->json(['message' => 'Post updated', 'post' => $post]);
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($post->image_path && Storage::disk('public')->exists($post->image_path)) {
            Storage::disk('public')->delete($post->image_path);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
}
