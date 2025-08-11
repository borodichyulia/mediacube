<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // Для API: возвращаем токен
            if ($request->wantsJson()) {
                $token = $request->user()->createToken('auth-token')->plainTextToken;
                return response()->json([
                    'message' => 'Logged in successfully',
                    'token' => $token
                ], 200);
            }

            // Для веба: редирект
            return redirect()->intended('/');
        }

        // Ошибка аутентификации
        return back()->withErrors([
            'email' => 'Неверные учетные данные.',
        ]);
    }

    public function logout(Request $request)
    {
//        Auth::logout();
//
//        $request->session()->invalidate();
//        $request->session()->regenerateToken();
//
//        return response()->json(['message' => 'Logged out successfully']);

        if ($request->user()) {
            $request->user()->tokens()->delete();
        }

        return response()->json(['message' => 'Logged out successfully']);
    }
}
