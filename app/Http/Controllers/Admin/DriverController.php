<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    /**
     * Display a listing of the drivers.
     */
    public function index(Request $request)
    {
        $query = Driver::query();

        // Tìm kiếm
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('driver_code', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('vehicle_number', 'like', "%{$search}%");
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Lọc theo loại xe
        if ($request->filled('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        // Lọc theo bằng lái sắp hết hạn
        if ($request->filled('license_expiring')) {
            $query->where('license_expiry', '<=', now()->addDays(30))
                ->where('license_expiry', '>', now());
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $drivers = $query->withCount(['orders', 'currentOrders', 'completedOrders'])
            ->paginate(15)
            ->appends($request->query());

        return view('admin.drivers.index', compact('drivers'));
    }

    /**
     * Show the form for creating a new driver.
     */
    public function create()
    {
        return view('admin.drivers.create');
    }

    /**
     * Store a newly created driver in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:drivers,email',
            'phone' => 'required|string|max:20|unique:drivers,phone',
            'address' => 'required|string',
            'license_number' => 'required|string|max:50|unique:drivers,license_number',
            'license_expiry' => 'required|date|after:today',
            'vehicle_type' => 'required|in:motorbike,small_truck,van',
            'vehicle_number' => 'required|string|max:20|unique:drivers,vehicle_number',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        Driver::create($validated);

        return redirect()->route('admin.drivers.index')
            ->with('success', '✅ Tài xế đã được thêm thành công!');
    }

    /**
     * Display the specified driver.
     */
    public function show(Driver $driver)
    {
        $driver->load(['orders' => function($query) {
            $query->latest()->with('orderItems.product');
        }, 'currentOrders', 'completedOrders']);

        // Thống kê
        $stats = [
            'total_orders' => $driver->orders()->count(),
            'completed_orders' => $driver->completedOrders()->count(),
            'current_orders' => $driver->currentOrders()->count(),
            'average_delivery_time' => $driver->completedOrders()
                ->whereNotNull('assigned_at')
                ->whereNotNull('delivered_at')
                ->get()
                ->avg(function($order) {
                    return $order->assigned_at->diffInMinutes($order->delivered_at);
                }),
            'on_time_deliveries' => $driver->completedOrders()
                ->where('delivered_at', '<=', now())
                ->count(),
            'total_revenue' => $driver->completedOrders()->sum('total_amount'),
        ];

        return view('admin.drivers.show', compact('driver', 'stats'));
    }

    /**
     * Show the form for editing the specified driver.
     */
    public function edit(Driver $driver)
    {
        return view('admin.drivers.edit', compact('driver'));
    }

    /**
     * Update the specified driver in storage.
     */
    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('drivers')->ignore($driver)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('drivers')->ignore($driver)],
            'address' => 'required|string',
            'license_number' => ['required', 'string', 'max:50', Rule::unique('drivers')->ignore($driver)],
            'license_expiry' => 'required|date',
            'vehicle_type' => 'required|in:motorbike,small_truck,van',
            'vehicle_number' => ['required', 'string', 'max:20', Rule::unique('drivers')->ignore($driver)],
            'status' => 'required|in:active,inactive,busy',
            'notes' => 'nullable|string',
        ]);

        $driver->update($validated);

        return redirect()->route('admin.drivers.show', $driver)
            ->with('success', '✅ Thông tin tài xế đã được cập nhật!');
    }

    /**
     * Remove the specified driver from storage.
     */
    public function destroy(Driver $driver)
    {
        // Kiểm tra xem tài xế có đơn hàng đang giao không
        if ($driver->currentOrders()->count() > 0) {
            return redirect()->route('admin.drivers.index')
                ->with('error', '❌ Không thể xóa tài xế đang có đơn hàng chưa hoàn thành!');
        }

        $driver->delete();

        return redirect()->route('admin.drivers.index')
            ->with('success', '✅ Tài xế đã được xóa thành công!');
    }

    /**
     * Toggle driver status
     */
    public function toggleStatus(Request $request, Driver $driver)
    {
        $newStatus = $request->status;

        if (!in_array($newStatus, ['active', 'inactive', 'busy'])) {
            return response()->json(['error' => 'Trạng thái không hợp lệ'], 400);
        }

        // Không cho phép chuyển sang busy nếu có đơn hàng đang giao
        if ($newStatus === 'inactive' && $driver->currentOrders()->count() > 0) {
            return response()->json(['error' => 'Không thể ngừng hoạt động khi đang có đơn hàng'], 400);
        }

        $driver->update([
            'status' => $newStatus,
            'last_active_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Trạng thái tài xế đã được cập nhật',
            'new_status' => $driver->status_name
        ]);
    }

    /**
     * Assign driver to order
     */
    public function assignToOrder(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);

        $order = Order::findOrFail($validated['order_id']);

        // Kiểm tra xem đơn hàng có thể gán tài xế không
        if (!$order->can_assign_driver) {
            return response()->json(['error' => 'Đơn hàng không thể gán tài xế'], 400);
        }

        // Kiểm tra xem tài xế có thể nhận đơn không
        if (!$driver->canTakeNewOrder()) {
            return response()->json(['error' => 'Tài xế không thể nhận thêm đơn hàng'], 400);
        }

        $order->assignDriver($driver);

        return response()->json([
            'success' => true,
            'message' => "Đã gán đơn hàng #{$order->order_number} cho tài xế {$driver->name}"
        ]);
    }

    /**
     * Get available drivers for assignment
     */
    public function getAvailableDrivers(Request $request)
    {
        $drivers = Driver::available()
            ->whereHas('currentOrders', function($query) {
                $query->havingRaw('COUNT(*) < 3');
            }, '<', 3)
            ->orWhereDoesntHave('currentOrders')
            ->select('id', 'driver_code', 'name', 'phone', 'vehicle_type', 'status')
            ->withCount('currentOrders')
            ->get();

        return response()->json($drivers);
    }

    /**
     * Bulk actions for drivers
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'driver_ids' => 'required|array',
            'driver_ids.*' => 'exists:drivers,id'
        ]);

        $drivers = Driver::whereIn('id', $validated['driver_ids']);

        switch ($validated['action']) {
            case 'activate':
                $drivers->update(['status' => 'active']);
                $message = 'Đã kích hoạt ' . count($validated['driver_ids']) . ' tài xế';
                break;

            case 'deactivate':
                // Không cho phép ngừng hoạt động nếu có đơn hàng đang giao
                $busyDrivers = $drivers->whereHas('currentOrders')->count();
                if ($busyDrivers > 0) {
                    return redirect()->back()->with('error', "❌ Có {$busyDrivers} tài xế đang có đơn hàng, không thể ngừng hoạt động!");
                }

                $drivers->update(['status' => 'inactive']);
                $message = 'Đã ngừng hoạt động ' . count($validated['driver_ids']) . ' tài xế';
                break;

            case 'delete':
                // Kiểm tra xem có tài xế nào đang có đơn hàng không
                $busyDrivers = $drivers->whereHas('currentOrders')->count();
                if ($busyDrivers > 0) {
                    return redirect()->back()->with('error', "❌ Có {$busyDrivers} tài xế đang có đơn hàng, không thể xóa!");
                }

                $drivers->delete();
                $message = 'Đã xóa ' . count($validated['driver_ids']) . ' tài xế';
                break;
        }

        return redirect()->route('admin.drivers.index')->with('success', "✅ {$message}");
    }

    /**
     * Export drivers data
     */
    public function export(Request $request)
    {
        $drivers = Driver::with('currentOrders', 'completedOrders')
            ->get()
            ->map(function($driver) {
                return [
                    'Mã tài xế' => $driver->driver_code,
                    'Tên' => $driver->name,
                    'Email' => $driver->email,
                    'Điện thoại' => $driver->phone,
                    'Địa chỉ' => $driver->address,
                    'Số bằng lái' => $driver->license_number,
                    'Hạn bằng lái' => $driver->license_expiry->format('d/m/Y'),
                    'Loại xe' => $driver->vehicle_type_name,
                    'Biển số xe' => $driver->vehicle_number,
                    'Trạng thái' => $driver->status_name,
                    'Đánh giá' => $driver->formatted_rating,
                    'Tổng đơn hàng' => $driver->total_deliveries,
                    'Đơn hiện tại' => $driver->current_orders_count,
                    'Ngày tạo' => $driver->created_at->format('d/m/Y H:i'),
                ];
            });

        $filename = 'drivers_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($drivers) {
            $file = fopen('php://output', 'w');

            // BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");

            // Headers
            if ($drivers->isNotEmpty()) {
                fputcsv($file, array_keys($drivers->first()));
            }

            // Data
            foreach ($drivers as $driver) {
                fputcsv($file, $driver);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Driver performance report
     */
    public function performanceReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $drivers = Driver::withCount([
            'orders as total_orders' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            },
            'completedOrders as completed_orders' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('delivered_at', [$startDate, $endDate]);
            }
        ])
            ->with(['completedOrders' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('delivered_at', [$startDate, $endDate])
                    ->select('driver_id', 'total_amount', 'assigned_at', 'delivered_at');
            }])
            ->get()
            ->map(function($driver) {
                $completedOrders = $driver->completedOrders;

                return [
                    'driver' => $driver,
                    'total_revenue' => $completedOrders->sum('total_amount'),
                    'average_delivery_time' => $completedOrders->avg(function($order) {
                        return $order->assigned_at && $order->delivered_at ?
                            $order->assigned_at->diffInMinutes($order->delivered_at) : 0;
                    }),
                    'on_time_percentage' => $completedOrders->count() > 0 ?
                        ($completedOrders->where('is_on_time_delivery', true)->count() / $completedOrders->count()) * 100 : 0
                ];
            });

        return view('admin.drivers.performance', compact('drivers', 'startDate', 'endDate'));
    }
}
