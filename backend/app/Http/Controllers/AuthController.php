<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Seed default categories
        $defaultCategories = [
            ['name' => 'เงินเดือน', 'type' => 'income', 'color' => '#10B981', 'icon' => 'BanknotesIcon'],
            ['name' => 'อาหาร', 'type' => 'expense', 'color' => '#F59E0B', 'icon' => 'CakeIcon'],
            ['name' => 'เดินทาง', 'type' => 'expense', 'color' => '#3B82F6', 'icon' => 'TruckIcon'],
            ['name' => 'ช้อปปิ้ง', 'type' => 'expense', 'color' => '#EC4899', 'icon' => 'ShoppingBagIcon'],
            ['name' => 'บิลและค่าใช้จ่าย', 'type' => 'expense', 'color' => '#EF4444', 'icon' => 'DocumentTextIcon'],
        ];

        foreach ($defaultCategories as $cat) {
            $user->categories()->create($cat);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'user' => $user
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            return response()->json([
                'user' => Auth::user(),
            ]);
        }

        throw ValidationException::withMessages([
            'email' => ['ข้อมูลการเข้าสู่ระบบไม่ถูกต้อง (Invalid credentials)'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out']);
    }
}
