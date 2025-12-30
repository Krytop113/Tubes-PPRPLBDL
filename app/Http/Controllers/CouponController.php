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
            ->latest()
            ->get();

        return view('customer.coupons.index', compact('coupons', 'usercoupons'));
    }

    public function claim($id)
    {
        $userId = Auth::id();

        try {
            $result = DB::select("CALL create_couponuser_procedure(?, ?)", [$userId, $id]);

            return redirect()->back()->with('success', 'Kupon berhasil diklaim!');
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();

            return redirect()->back()->with('error', 'Gagal klaim: ' . $errorMessage);
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        //
    }
}
