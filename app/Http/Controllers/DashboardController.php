<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ingredient;
use App\Models\Role;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $userRoleId = Role::where('name', 'customer')->value('id');
        $employeeRoleId = Role::where('name', 'employee')->value('id');

        $totalUsers = User::where('role_id', $userRoleId)->count();

        $totalEmployees = User::where('role_id', $employeeRoleId)->count();

        $totalSales = Payment::sum('total_amount');

        $lowStockItems = Ingredient::whereColumn('stock_quantity', '<=', 'minimum_stock_level')->get();

        return view('control.dashboard', compact(
            'totalUsers',
            'totalEmployees',
            'totalSales',
            'lowStockItems'
        ));
    }
}
