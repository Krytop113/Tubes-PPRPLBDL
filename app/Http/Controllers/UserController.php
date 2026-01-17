<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
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
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
        ]);

        DB::beginTransaction();
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

            DB::commit();

            return redirect()->route('editProfile')
                ->with('success', 'Profil Anda berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors("Gagal update profil User ID {$user->id}");
        }
    }

    // Control Panel
    public function indexCustomer()
    {
        $user = User::where('role_id', '3')->get();

        return view('control.customer.index', compact('user'));
    }


    public function indexEmployee()
    {
        $employee = User::with('role')->where('role_id', '2')->get();

        return view('control.employee.index', compact('employee'));
    }


    public function createEmployee()
    {
        return view('control.employee.create');
    }


    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'password' => 'required|min:6'
        ]);

        DB::beginTransaction();
        try {
            $result = DB::select(
                'CALL create_employee_procedure(?, ?, ?, ?, ?)',
                [
                    $request->name,
                    $request->email,
                    $request->phone_number,
                    Hash::make($request->password),
                    $request->date_of_birth
                ]
            );

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new Exception($result[0]->ErrorDetail);
            }

            DB::commit();

            return redirect()->route('control.employee')
                ->with('success', 'Karyawan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function destroyEmployee($id)
    {
        DB::beginTransaction();
        try {
            $result = DB::select('CALL delete_employee_procedure(?)', [$id]);
            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new Exception($result[0]->ErrorDetail);
            }

            DB::commit();
            return back()->with('success', 'Karyawan berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
