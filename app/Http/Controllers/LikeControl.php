<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Like;

class LikeControl extends Controller
{
    public function store_post(Request $request, $id)
    {
        $input = $request->validate([
            'type' => 'required|string',
        ]);

        $select = DB::table('Likes')
            ->select("*")
            ->where('author', '=', Auth::user()->login)
            ->where('status_id', '=', $id)->get();

        if (count($select) == 0) {
            $like = Like::create([
                'author' => Auth::user()->login,
                'status' => 'post',
                'status_id' => $id,
                'type' => $input['type']
            ]);

            $response = [
                'like' => $like
            ];
            return response($response, 201);
        } else {
            return response([
                'message' => 'You can not make more than 1 like'
            ], 401);
        }
    }

    public function store_com(Request $request, $id)
    {
        $input = $request->validate([
            'type' => 'required|string',
        ]);

        $select = DB::table('Likes')
            ->select("*")
            ->where('author', '=', Auth::user()->login)
            ->where('status_id', '=', $id)->get();

        if (count($select) == 0) {
            $like = Like::create([
                'author' => Auth::user()->login,
                'status' => 'comment',
                'status_id' => $id,
                'type' => $input['type']
            ]);

            $response = [
                'like' => $like
            ];
            return response($response, 201);
        } else {
            return response([
                'message' => 'You can not make more than 1 like'
            ], 401);
        }
    }

    public function show_post($id)
    {
        return  Like::where('status', 'post')
            ->where('status_id', $id)->get();
    }

    public function show_comment($id)
    {
        return Like::where('status', 'comment')
            ->where('status_id', $id)->get();
    }

    public function update(Request $request, $id)
    {
        $input = $request->validate([
            'type' => 'required|string',
        ]);

        $select = DB::table('Likes')
            ->select("*")
            ->where('author', '=', Auth::user()->login)
            ->where('status_id', '=', $id)->get();

        if (count($select) == 0) {
            $like = Like::create([
                'author' => Auth::user()->login,
                'status' => 'comment',
                'status_id' => $id,
                'type' => $input['type']
            ]);

            $response = [
                'like' => $like
            ];
            return response($response, 201);
        } else {
            return response([
                'message' => 'You can not make more than 1 like'
            ], 401);
        }
    }

    public function destroy_post($id)
    {
        $like = DB::table('Likes')
            ->select("*")
            ->where('status', '=', 'post')
            ->where('author', '=', Auth::user()->login)
            ->where('status_id', '=', $id)->get();

        if (count($like) == 0) {
            $response = [
                'message' => 'Its like is not yours or it does not exist'
            ];

            return response($response, 401);
        } else {
            return Like::destroy(($like->pluck('id')[0]));
        }
    }
    public function destroy_com($id)
    {
        $like = DB::table('Likes')
            ->select("*")
            ->where('status', '=', 'comment')
            ->where('author', '=', Auth::user()->login)
            ->where('status_id', '=', $id)->get();

        if (count($like) == 0) {
            $response = [
                'message' => 'Its like is not yours or it does not exist'
            ];

            return response($response, 401);
        } else {
            return Like::destroy(($like->pluck('id')[0]));
        }
    }
}
