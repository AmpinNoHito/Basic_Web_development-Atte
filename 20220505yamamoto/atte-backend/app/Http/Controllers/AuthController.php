<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'ユーザー登録が完了しました。'
        ], 200);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return response()->json([
                'message' => 'ログインしました。ユーザー名: '.Auth::user()->name,
                'userInfo' => Auth::user(),
            ], 200);
        } else {
            return response()->json(['message' => 'ログインに失敗しました。メールアドレスとパスワードをご確認のうえ再度お試しください。'], 400);
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'ログアウトしました。'
        ], 200);
    }
}
