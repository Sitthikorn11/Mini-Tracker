<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function updateInfo(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Email is deliberately omitted to prevent changing it.
        ]);

        $user = $request->user();
        $user->update(['name' => $validated['name']]);

        return response()->json($user);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed', // expects new_password_confirmation
        ]);

        $user = $request->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The provided password does not match your current password.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return response()->json(['message' => 'Password updated successfully.']);
    }

    public function updateBudget(Request $request)
    {
        $validated = $request->validate([
            'monthly_budget' => 'required|numeric|min:0',
        ]);

        $user = $request->user();
        $user->update(['monthly_budget' => $validated['monthly_budget']]);

        return response()->json(['message' => 'Budget updated successfully.', 'monthly_budget' => $user->monthly_budget]);
    }
}
