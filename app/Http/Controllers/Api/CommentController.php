<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $post = Post::findOrFail($postId);

        if (!$post->allow_comment) {
            return response()->json(['message' => 'Komentar dinonaktifkan pada post ini.'], 403);
        }

        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'post_id' => $postId,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Komentar ditambahkan.',
            'comment' => $comment,
        ]);
    }

    public function index($postId)
    {
        $comments = Comment::where('post_id', $postId)
            ->with('user:id,username')
            ->orderBy('created_at')
            ->get();

        return response()->json($comments);
    }

    public function destroy($id, Request $request)
    {
        $comment = Comment::findOrFail($id);
        $user = $request->user();

        if ($comment->user_id !== $user->id && $comment->post->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();
        return response()->json(['message' => 'Komentar dihapus']);
    }
}
