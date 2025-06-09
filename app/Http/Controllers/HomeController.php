<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class HomeController extends Controller
{
    public function index()
    {
        $ordersToRate = Auth::check() ? Order::where('user_id', Auth::id())
            ->where('status', Order::STATUS_DELIVERED)
            ->whereDoesntHave('driverRating')
            ->with(['driver' => function ($query) {
                $query->withAvg('ratings', 'rating');
            }])
            ->get() : collect(); // Trả về collection rỗng nếu không đăng nhập

        return view('welcome', compact('ordersToRate'));
    }
}