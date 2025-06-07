<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Lấy đơn hàng gần đây của user (nếu có)
        $user_orders = collect(); // Khởi tạo collection rỗng
        if ($user) {
            $user_orders = Order::where('user_id', $user->id)
                ->latest()
                ->take(3)
                ->get();
        }

        // Lấy sản phẩm nổi bật
        $featured_products = Product::with('category')
            ->active()
            ->featured()
            ->take(4)
            ->get();

        return view('dashboard', compact('user_orders', 'featured_products'));
    }
}
