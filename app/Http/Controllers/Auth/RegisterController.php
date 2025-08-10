<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    // Показ формы регистрации
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Обработка регистрации (API + Web)
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Автоматический вход после регистрации
        Auth::login($user);

        // Для API: возвращаем токен
        if ($request->wantsJson()) {
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json([
                'message' => 'User registered',
                'token' => $token,
            ], 201);
        }

        // Для веба: редирект
        return redirect('/');
    }
}
