<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Exception;

class UserController extends Controller
{
    public function editProfile(User $user)
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
        ]);

        try {
            $result = DB::select(
                'CALL edit_user_profile(?, ?, ?, ?, ?)',
                [
                    $user->id,
                    $request->name,
                    $request->email,
                    $request->phone_number,
                    $request->date_of_birth,
                ]
            );

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new Exception($result[0]->ErrorDetail);
            }

            return redirect()->route('editProfile')
                ->with('success', 'Profil Anda berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors("Gagal update profil User ID {$user->id}: " . $e->getMessage());
        }
    }
}
