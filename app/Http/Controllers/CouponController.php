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
            return redirect()->back()->with('error', 'Gagal klaim: ' . $e);
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
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(Coupon $coupon)
    {
        //
    }

    public function update(Request $request, Coupon $coupon)
    {
        //
    }

    public function destroy(Coupon $coupon)
    {
        //
    }
}
