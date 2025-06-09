<?php

namespace App\Http\Controllers;

use App\Models\DriverRating;
use App\Models\Order;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverRatingController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'driver_id' => 'required|exists:drivers,id',
        'user_id' => 'required|exists:users,id',
        'rating' => 'required|integer|between:1,5',
        'comment' => 'nullable|string|max:500',
    ]);

    $order = Order::findOrFail($request->order_id);

    if ($order->user_id !== Auth::id()) {
        return response()->json([
            'success' => false,
            'message' => 'Bạn không có quyền đánh giá đơn hàng này.'
        ], 403);
    }

    if ($order->driverRating) {
        return response()->json([
            'success' => false,
            'message' => 'Đơn hàng đã được đánh giá.'
        ], 422);
    }

    $driverRating = DriverRating::create([
        'order_id' => $request->order_id,
        'driver_id' => $request->driver_id,
        'user_id' => $request->user_id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    $driver = Driver::findOrFail($request->driver_id);
    $averageRating = DriverRating::where('driver_id', $request->driver_id)->avg('rating');
    $driver->update(['average_rating' => round($averageRating, 1)]);

    return response()->json([
        'success' => true,
        'message' => 'Đánh giá đã được gửi!',
        'average_rating' => $driver->average_rating
    ]);
}
    public function skipDriverRating(Request $request)
    {
        $orderToRate = Order::where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->whereDoesntHave('driverRating') // Sử dụng relationship thay vì join
            ->latest('delivered_at')
            ->with('driver')
            ->first();

        if ($orderToRate) {
            $orderToRate->update(['skipped_rating_at' => now()]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã bỏ qua đánh giá.',
            ]);
        }

        session(['skip_rating' => true]);
        return redirect()->route('home')->with('success', 'Đã bỏ qua đánh giá.');
    }
}
