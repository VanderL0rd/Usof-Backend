<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;


class AuthControl extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate(
            [
                'full_name' => 'required|string',
                'login' => 'required|string|unique:users,login',
                'password' => 'required|string|confirmed',
                'email' => 'required|string|unique:users,email'
            ]
        );

        $user = User::create([
            'full_name' => $fields['full_name'],
            'login' => $fields['login'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password'])
        ]);
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate(
            [
                'login' => 'required|string',
                'password' => 'required|string',
            ]
        );

        $user = User::where('login', $fields['login'])->first();
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'There is no user with this login',
            ]);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out',
        ];
    }

    public function pass_reset(Request $request)
    {
        $shtoto = $request->validate(['email' => 'required|string']);

        Password::sendResetLink($shtoto);

        return [
            'message' => 'email send',
        ];
    }

    public function pass_reset_check(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
