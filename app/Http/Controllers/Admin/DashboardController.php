<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders   = Order::count();
        $totalServices = Service::count();
        $totalUsers    = User::count();
        $recentOrders  = Order::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalOrders', 'totalServices', 'totalUsers', 'recentOrders'
        ));
    }
}