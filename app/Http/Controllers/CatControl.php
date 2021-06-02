<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Cat;
use App\Models\Post;
use App\Models\CatPost;
use Illuminate\Support\Facades\DB;

class CatControl extends Controller
{
    public function index()
    {
        return Cat::all();
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'title' => 'required|string|unique:cats,title'
        ]);
        $cat = Cat::create([
            'title' => $input['title']
        ]);
        return response([
            'category' => $cat
        ], 201);
    }

    public function show($id)
    {
        return Cat::where('id', $id)->get();
    }

    public function update(Request $request, $id)
    {
        $cat = Cat::find($id);
        $cat->update($request->all());
        return $cat;
    }

    public function destroy($id)
    {
        return Cat::destroy($id);
    }

    public function show_posts($id)
    {
        $id_cat = Cat::all()->where('id', $id)->first()['id'];
        $table_cat = CatPost::all()->where('c_id', $id_cat)->pluck('p_id');
        for ($i = 0; $i < count($table_cat); $i++) {
            $posts[] = Post::all()->where('id', $table_cat[$i]);
        }
        return $posts;
    }
}
