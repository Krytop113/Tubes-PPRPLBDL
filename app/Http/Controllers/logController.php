<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Log;

class logController extends Controller
{
    public function index()
    {
        $query = Log::query();

        $search = request('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('pengguna', 'like', "%{$search}%")
                    ->orWhere('user_id', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (request()->filled('start_date') && request()->filled('end_date')) {
            $query->whereBetween('created_at', [
                request('start_date'),
                request('end_date')
            ]);
        }

        $log = $query->latest()->get();

        return view('control.log.index', compact('log', 'search'));
    }
}
