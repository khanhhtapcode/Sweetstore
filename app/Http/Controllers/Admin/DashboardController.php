<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Thống kê tổng quan
        $totalOrders = \App\Models\Order::count();
        $totalRevenue = \App\Models\Order::where('status', 'delivered')->sum('total_amount');
        $totalProducts = \App\Models\Product::count();
        $totalUsers = \App\Models\User::where('role', 'user')->count();
        $totalDrivers = \App\Models\Driver::count();
        $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
        $activeDrivers = \App\Models\Driver::where('status', 'active')->count();
        $lowStockProducts = \App\Models\Product::where('stock_quantity', '<=', 10)->count();

        // Đơn hàng gần đây
        $recentOrders = \App\Models\Order::with(['user', 'driver'])
            ->latest()
            ->limit(5)
            ->get();

        // Sản phẩm sắp hết hàng
        $lowStockItems = \App\Models\Product::where('stock_quantity', '<=', 10)
            ->orderBy('stock_quantity', 'asc')
            ->limit(5)
            ->get();

        // Tài xế hoạt động
        $activeDriversList = \App\Models\Driver::where('status', 'active')
            ->withCount('currentOrders')
            ->limit(5)
            ->get();

        // Thống kê doanh thu hôm nay
        $todayRevenue = \App\Models\Order::whereDate('created_at', today())
            ->where('status', 'delivered')
            ->sum('total_amount');

        // Đơn hàng hôm nay
        $todayOrders = \App\Models\Order::whereDate('created_at', today())->count();

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'totalUsers',
            'totalDrivers',
            'pendingOrders',
            'activeDrivers',
            'lowStockProducts',
            'recentOrders',
            'lowStockItems',
            'activeDriversList',
            'todayRevenue',
            'todayOrders'
        ));
    }

    public function revenueData(Request $request)
    {
        $startDate = $request->get('start_date') ?? Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->get('end_date') ?? Carbon::now()->endOfMonth()->toDateString();

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $diffInDays = $start->diffInDays($end);

        if ($diffInDays <= 30) {
            // Theo ngày
            $data = DB::table('orders')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
                ->whereBetween('created_at', [$startDate, $endDate])
                // ->where('status', 'delivered')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        } elseif ($diffInDays <= 90) {
            // Theo tuần
            $data = DB::table('orders')
                ->select(DB::raw('YEARWEEK(created_at, 1) as week'), DB::raw('SUM(total_amount) as total'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'delivered')
                ->groupBy('week')
                ->orderBy('week')
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => 'Tuần ' . substr($item->week, 4) . ' - ' . substr($item->week, 0, 4),
                        'total' => $item->total
                    ];
                });
        } elseif ($diffInDays <= 730) {
            // Theo tháng
            $data = DB::table('orders')
                ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as date'), DB::raw('SUM(total_amount) as total'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'delivered')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        } else {
            // Theo năm
            $data = DB::table('orders')
                ->select(DB::raw('YEAR(created_at) as date'), DB::raw('SUM(total_amount) as total'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'delivered')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
        }

        return response()->json($data);
    }

    public function topProducts()
    {
        $topProducts = \App\Models\OrderItem::selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product:id,name')
            ->take(5)
            ->get();

        return response()->json($topProducts);
    }

    public function topCustomers()
    {
        $topCustomers = \App\Models\User::select('users.id', 'users.name')
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->whereNotNull('orders.total_amount') // thay vì lọc status
            ->groupBy('users.id', 'users.name')
            ->selectRaw('SUM(orders.total_amount) as total_spent')
            ->orderByDesc('total_spent')
            ->limit(5)
            ->get();

        return response()->json($topCustomers);
    }

    //Dữ liệu đơn hàng theo trạng thái
    public function getOrderStatusData()
    {
        $orderStatusData = \App\Models\Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->orderBy('total', 'desc')
            ->get();

        return response()->json($orderStatusData);
    }

    /**
     * Get driver performance data
     */
    public function getDriverPerformance()
    {
        $driverPerformance = \App\Models\Driver::with(['completedOrders' => function($query) {
            $query->whereBetween('delivered_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
        }])
            ->where('status', '!=', 'inactive')
            ->get()
            ->map(function($driver) {
                $completedThisMonth = $driver->completedOrders->count();
                $totalRevenue = $driver->completedOrders->sum('total_amount');

                return [
                    'name' => $driver->name,
                    'driver_code' => $driver->driver_code,
                    'completed_orders' => $completedThisMonth,
                    'total_revenue' => $totalRevenue,
                    'rating' => $driver->rating ?? 0,
                    'status' => $driver->status_name
                ];
            })
            ->sortByDesc('completed_orders')
            ->take(10)
            ->values();

        return response()->json($driverPerformance);
    }
}
