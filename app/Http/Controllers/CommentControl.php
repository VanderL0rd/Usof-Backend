<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;

class CommentControl extends Controller
{

    public function store(Request $request, $id)
    {
        $input = $request->validate([
            'content' => 'required|string'
        ]);

        $post = Post::where('id', $id)->first();

        $comment = Comment::create([
            'content' => $input['content'],
            'author' => Auth::user()->login,
            'post_id' => $post['id']
        ]);

        $response = [
            'comment' => $comment
        ];

        return response($response, 201);
    }

    public function show($id)
    {
        return  Comment::where('id', $id)->get();
    }

    public function show_spec($id)
    {
        return  Comments::where('post_id', $id)->get();
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail(auth()->user()->id)['login'];
        $comment = Comment::find($id);
        if (!$comment || $comment['author'] != $user) {
            $response = [
                'message' => 'Its comment is not yours or it does not exist'
            ];

            return response($response, 401);
        } else {
            $comment->update($request->all());
            return $comment;
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail(auth()->user()->id)['login'];
        $comment = Comment::find($id);
        if (!$comment || $comment['author'] != $user) {
            $response = [
                'message' => 'Its comment is not yours or it does not exist'
            ];

            return response($response, 401);
        } else {
            return Comment::destroy($id);
        }
    }
}
