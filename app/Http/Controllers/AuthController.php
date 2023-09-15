<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|max:15|unique:users',
            'email' => 'required|string|unique:users|email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'username' => $fields['username'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
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
        $input = $request->input('username');

        //Check if entered username is an email or a username(twitter handle)
        //twitter accepts email or twitter handle as login credential together with password
        if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
            $fields = $request->validate([
                'username' => 'required|email',
                'password' => 'required|string'
            ]);
            $user = User::where('email', $fields['username'])->first();
        } else {
            $fields = $request->validate([
                'username' => 'required|string|regex:/\w*$/|max:15',
                'password' => 'required|string'
            ]);
            $user = User::where('username', $fields['username'])->first();
        };

        // $fields = $request->validate([
        //     'email' => 'required|email',
        //     'username' => 'required|string|max:15',
        //     'password' => 'required|string'
        // ]);

        //Check email or username
        // $user = User::where('email', $fields['email'])->orWhere('username', $fields['username'])->first();

        //Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
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
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized uwu'], 401);
        }

        $user = Auth::user();
        $user->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
