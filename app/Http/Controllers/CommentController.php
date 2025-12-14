<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Planning;
use App\Notifications\CommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Planning $planning)
    {
        $request->validate([
            'body' => 'required',
        ]);

        $comment = $planning->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        if ($planning->user_id !== Auth::id()) {
            $planning->user->notify(new CommentNotification($comment));
        }

        return back();
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back();
    }
}
