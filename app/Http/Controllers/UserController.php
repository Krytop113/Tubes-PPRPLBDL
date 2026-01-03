<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function editProfile(User $user)
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'username'       => 'sometimes|string|max:50',
            'email'          => 'sometimes|email|unique:users,email,' . $user->id,
            'phone_number'   => 'sometimes|string|max:20',
            'date_of_birth'  => 'sometimes|nullable|date',
        ]);

        $data = [
            'username'      => $validated['username'] ?? $user->username,
            'email'         => $validated['email'] ?? $user->email,
            'phone_number'  => $validated['phone_number'] ?? $user->phone_number,
            'date_of_birth' => $validated['date_of_birth'] ?? $user->date_of_birth,
        ];

        DB::statement(
            'CALL update_user_profile(?, ?, ?, ?, ?)',
            [
                $user->id,
                $data['username'],
                $data['email'],
                $data['phone_number'],
                $data['date_of_birth'],
            ]
        );

        return view('customer/home')->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
