<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Cat;
use App\Models\User;
use App\Models\CatPost;


class PostControl extends Controller
{
    public function index()
    {
        return Post::all();
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'category' => 'required|string'
        ]);

        $cat = Cat::where('title', $input['category'])->first();
        if (!$cat) {
            return response([
                'message' => 'No such category'
            ], 401);
        }

        $post = Post::create([
            'title' => $input['title'],
            'content' => $input['content'],
            'author' => Auth::user()->login
        ]);

        $idPost = DB::table('Posts')->select("*")->get()->max("id");
        $category1 = Cat::all()->where('title', $input['category'])->first()['id'];
        CatPost::create([
            'p_id' => $idPost,
            'c_id' => $category1
        ]);
        $response = [
            'post' => $post
        ];
        return response($response, 201);
    }

    public function show($id)
    {
        return  Post::where('id', $id)->get();
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail(auth()->user()->id)['login'];
        $post = Post::find($id);
        if (!$post || $post['author'] != $user) {
            $response = [
                'message' => 'Its post is not yours or it does not exist'
            ];
            return response($response, 401);
        } else {
            $post->update($request->all());
            return $post;
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail(auth()->user()->id)['login'];
        $post = Post::find($id);
        if (!$post || $post['author'] != $user) {
            $response = [
                'message' => 'Its post is not yours or it does not exist'
            ];

            return response($response, 401);
        } else {
            return Post::destroy($id);
        }
    }

    public function show_cats($id)
    {
        $id_post = Post::all()->where('id', $id)->first()['id'];
        $table_cat = CatPost::all()->where('p_id', $id_post)->pluck('c_id');
        for ($i = 0; $i < count($table_cat); $i++) {
            $cats[] = Cat::all()->where('id', $table_cat[$i]);
        }
        return $cats;
    }
}
