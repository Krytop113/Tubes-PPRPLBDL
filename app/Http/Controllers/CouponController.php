<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Models\CouponUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class CouponController extends Controller
{
    // Customer View
    public function index()
    {
        $userId = Auth::id();

        $coupons = Coupon::whereDoesntHave('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('end_date', '>=', now())
            ->get();

        $usercoupons = CouponUser::with('coupon')
            ->where('user_id', $userId)
            ->where('status', 'unused')
            ->get();

        return view('customer.coupons.index', compact('coupons', 'usercoupons'));
    }

    public function claim($id)
    {
        $userId = Auth::id();

        DB::beginTransaction();
        try {
            $result = DB::select("CALL create_couponuser_procedure(?, ?)", [$userId, $id]);

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new Exception($result[0]->ErrorDetail);
            }
            DB::commit();

            return redirect()->back()->with('success', 'Kupon berhasil diklaim!');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('Terjadi error ketika menerima Kupon');
        }
    }

    // Control Panel View
    public function indexcontrol()
    {
        $coupons = Coupon::all();
        return view('control.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('control.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        try {
            $result = DB::select("CALL create_coupon_procedure(?, ?, ?, ?, ?)", [
                $request->title,
                $request->description,
                $request->discount_percentage,
                $request->start_date,
                $request->end_date
            ]);

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new Exception($result[0]->ErrorDetail);
            }

            return redirect()->route('control.coupons.index')
                ->with('success', 'Kupon ' . $request->title . ' berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withErrors('Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Coupon $coupon)
    {
        if (empty($coupon)) {
            return redirect()->route('control.coupons.index')->with('error', 'Data tidak ditemukan');
        }

        return view('control.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        try {
            $result = DB::select('CALL edit_coupon_procedure(?, ?, ?, ?, ?, ?)', [
                $coupon->id,
                $request->title,
                $request->description,
                $request->discount_percentage,
                $request->start_date,
                $request->end_date
            ]);

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new Exception($result[0]->ErrorDetail);
            }

            return redirect()->route('control.coupons.index')
                ->with('success', 'Kupon ' . $coupon->title . ' berhasil diperbarui!');
        } catch (Exception $e) {
            return back()->withErrors('Gagal memperbarui: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Coupon $coupon)
    {
        try {
            $result = DB::select('CALL delete_coupon_procedure(?)', [$coupon->id]);

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new Exception($result[0]->ErrorDetail);
            }

            return redirect()->route('control.coupons.index')->with('success', $coupon->title . ' Berhasil dihapus!');
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
