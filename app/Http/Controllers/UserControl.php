<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use App\Models\User;

class UserControl extends Controller
{
    public function index()
    {
        return User::all();
    }
    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);
        $file = $uploadedFile->storeAs($folder, $name . '.'
            . $uploadedFile->getClientOriginalExtension(), $disk);
        return $file;
    }

    public function update_picture(Request $request)
    {
        $request->validate([
            'prof_picture'  => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $user = User::findOrFail(auth()->user()->id);
        $login = Auth::user()->login;

        $image = $request->file('prof_picture');
        $name = $login . "_" . time();
        $folder = '/picture/avatar';
        $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
        $this->uploadOne($image, $folder, 'public', $name);
        $user->prof_picture = $filePath;
        $user->save();
        return [
            'message' => 'Profile picture: ' . $filePath,
        ];
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $login)
    {
        $user = DB::table('users')->select('*')->where('login', '=', $login);
        if (($user->pluck('login'))[0] === Auth::user()->login) {
            $user->update($request->all());
            return $user;
        } else {
            return response([
                'message' => 'Failed to update profile'
            ], 401);
        }
    }

    public function destroy($id)
    {
        auth()->user()->tokens()->delete();
        return User::destroy($id);
    }

    public function search($login)
    {
        return  User::where('login', $login)->get();
    }
}
