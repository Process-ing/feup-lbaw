<?php

namespace App\Http\Controllers\Api;

use App\Events\CommentEvent;
use App\Events\CommentLikeEvent;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiCommentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $visiblePosts = Post::visibleTo($user)->get();
        $comments = Comment::whereIn('post_id', $visiblePosts->pluck('id'))
            ->select(['id', 'content', 'post_id', 'author_id', 'timestamp', 'likes'])
            ->get();

        return response()->json($comments);
    }

    public function show(Request $request, $id)
    {
        $user = auth()->user();
        $visiblePosts = Post::visibleTo($user)->get();
        $comments = Comment::whereIn('post_id', $visiblePosts->pluck('id'))
            ->select(['id', 'content', 'post_id', 'author_id', 'timestamp', 'likes'])
            ->get();
        $comment = $comments->find($id);

        return response()->json($comment);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Comment::class);

        $request->validate([
            'content' => 'required|string',
            'post_id' => 'required|integer|exists:post,id',
        ]);

        $comment = new Comment;
        $comment->content = $request->input('content');
        $comment->post_id = $request->input('post_id');
        $comment->author_id = auth()->id();

        $comment->timestamp = now();
        $comment->likes = 0;
        $comment->save();

        event(new CommentEvent($comment->post_id, $comment->post->author_id));

        if ($request->accepts('text/html')) {
            return view('partials.comment-card', ['comment' => $comment]);
        } else {
            return response()->json($comment, 201);
        }
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string',
        ]);

        $comment->content = $request->input('content');
        $comment->save();

        if ($request->accepts('text/html')) {
            return view('partials.comment-card', ['comment' => $comment]);
        } else {
            return response()->json($comment);
        }
    }

    public function like(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $this->authorize('like', $comment);

        if (CommentLike::where('comment_id', $id)->where('liker_id', auth()->id())->exists()) {
            return response()->json(['error' => 'You have already liked this comment'], 409);
        }

        $like = new CommentLike;

        $like->liker_id = Auth::id();
        $like->comment_id = $id;

        $like->save();
        event(new CommentLikeEvent($comment->post_id, $comment->author_id));

        return response()->json($like, 201);
    }

    public function unlike(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $this->authorize('like', $comment);

        $like = CommentLike::where('comment_id', $id)
            ->where('liker_id', Auth::id())
            ->first();

        if (! $like) {
            return response()->json(['error' => 'You have not liked this comment'], 409);
        }

        $like->delete();

        return response()->json(['message' => 'Comment unliked successfully']);
    }

    public function destroy(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully.']);
    }
}
