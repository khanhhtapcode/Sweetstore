<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by order number, customer name or email
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('order_number', 'like', '%' . $searchTerm . '%')
                    ->orWhere('customer_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('customer_email', 'like', '%' . $searchTerm . '%');
            });
        }

        // Sort by latest first
        $query->latest();

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::active()->orderBy('name')->get();
        return view('admin.orders.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:1000',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatuses())),
            'payment_method' => 'nullable|in:cod,bank_transfer,credit_card',
            'notes' => 'nullable|string|max:1000'
        ], [
            'customer_name.required' => 'Tên khách hàng là bắt buộc.',
            'customer_email.required' => 'Email là bắt buộc.',
            'customer_phone.required' => 'Số điện thoại là bắt buộc.',
            'customer_address.required' => 'Địa chỉ giao hàng là bắt buộc.',
            'products.required' => 'Phải có ít nhất một sản phẩm.',
            'products.*.product_id.required' => 'Sản phẩm là bắt buộc.',
            'products.*.quantity.required' => 'Số lượng là bắt buộc.',
            'products.*.price.required' => 'Giá là bắt buộc.',
            'total_amount.required' => 'Tổng tiền là bắt buộc.',
            'status.required' => 'Trạng thái là bắt buộc.',
        ]);

        DB::beginTransaction();
        try {
            // Tạo đơn hàng
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'total_amount' => $request->total_amount,
                'status' => $request->status,
                'payment_method' => $request->payment_method ?? 'cod',
                'notes' => $request->notes
            ]);

            // Tạo order items và cập nhật tồn kho
            foreach ($request->products as $productData) {
                if (empty($productData['product_id']) || empty($productData['quantity'])) {
                    continue;
                }

                $product = Product::find($productData['product_id']);
                if (!$product) {
                    throw new \Exception("Sản phẩm không tồn tại.");
                }

                // Kiểm tra tồn kho
                if ($product->stock_quantity < $productData['quantity']) {
                    throw new \Exception("Sản phẩm '{$product->name}' chỉ còn {$product->stock_quantity} trong kho.");
                }

                // Tạo order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'price' => $productData['price']
                ]);

                // Trừ tồn kho nếu đơn hàng không phải pending hoặc cancelled
                if (!in_array($order->status, ['pending', 'cancelled'])) {
                    $product->decrement('stock_quantity', $productData['quantity']);
                }
            }

            DB::commit();
            return redirect()->route('admin.orders.index')
                ->with('success', 'Đơn hàng đã được tạo thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product.category']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $products = Product::orderBy('name')->get();
        $order->load('orderItems.product');
        return view('admin.orders.edit', compact('order', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:1000',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatuses())),
            'payment_method' => 'nullable|in:cod,bank_transfer,credit_card',
            'notes' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $order->status;

            // Hoàn lại tồn kho của order items cũ (trừ khi là pending hoặc cancelled)
            if (!in_array($oldStatus, ['pending', 'cancelled'])) {
                foreach ($order->orderItems as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            // Xóa order items cũ
            $order->orderItems()->delete();

            // Cập nhật thông tin đơn hàng
            $order->update([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'total_amount' => $request->total_amount,
                'status' => $request->status,
                'payment_method' => $request->payment_method ?? 'cod',
                'notes' => $request->notes
            ]);

            // Tạo order items mới
            foreach ($request->products as $productData) {
                if (empty($productData['product_id']) || empty($productData['quantity'])) {
                    continue;
                }

                $product = Product::find($productData['product_id']);
                if (!$product) {
                    throw new \Exception("Sản phẩm không tồn tại.");
                }

                // Kiểm tra tồn kho
                if ($product->stock_quantity < $productData['quantity']) {
                    throw new \Exception("Sản phẩm '{$product->name}' chỉ còn {$product->stock_quantity} trong kho.");
                }

                // Tạo order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                    'price' => $productData['price']
                ]);

                // Trừ tồn kho nếu đơn hàng không phải pending hoặc cancelled
                if (!in_array($request->status, ['pending', 'cancelled'])) {
                    $product->decrement('stock_quantity', $productData['quantity']);
                }
            }

            DB::commit();
            return redirect()->route('admin.orders.index')
                ->with('success', 'Đơn hàng đã được cập nhật thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        DB::beginTransaction();
        try {
            $orderNumber = $order->order_number;

            // Hoàn lại tồn kho nếu cần
            if (!in_array($order->status, ['pending', 'cancelled'])) {
                foreach ($order->orderItems as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            // Xóa order items trước
            $order->orderItems()->delete();

            // Xóa đơn hàng
            $order->delete();

            DB::commit();
            return redirect()->route('admin.orders.index')
                ->with('success', "Đơn hàng {$orderNumber} đã được xóa thành công!");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Update order status - Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatuses()))
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $order->status;
            $newStatus = $request->status;

            // Nếu chuyển từ trạng thái đã hủy sang trạng thái khác, cần trừ lại tồn kho
            if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                foreach ($order->orderItems as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        if ($product->stock_quantity >= $item->quantity) {
                            $product->decrement('stock_quantity', $item->quantity);
                        } else {
                            throw new \Exception("Sản phẩm '{$product->name}' không đủ tồn kho.");
                        }
                    }
                }
            }

            // Nếu chuyển từ pending sang trạng thái khác (trừ cancelled), cần trừ tồn kho
            if ($oldStatus === 'pending' && !in_array($newStatus, ['pending', 'cancelled'])) {
                foreach ($order->orderItems as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        if ($product->stock_quantity >= $item->quantity) {
                            $product->decrement('stock_quantity', $item->quantity);
                        } else {
                            throw new \Exception("Sản phẩm '{$product->name}' không đủ tồn kho.");
                        }
                    }
                }
            }

            // Nếu chuyển sang trạng thái hủy, hoàn lại tồn kho
            if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled' && $oldStatus !== 'pending') {
                foreach ($order->orderItems as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock_quantity', $item->quantity);
                    }
                }
            }

            $order->update(['status' => $newStatus]);

            DB::commit();

            $oldStatusName = Order::getStatuses()[$oldStatus] ?? $oldStatus;
            $newStatusName = Order::getStatuses()[$newStatus] ?? $newStatus;

            return redirect()->back()
                ->with('success', "Đơn hàng {$order->order_number} đã được chuyển từ '{$oldStatusName}' sang '{$newStatusName}'!");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Thống kê đơn hàng theo trạng thái
     */
    public function statistics()
    {
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'preparing' => Order::where('status', 'preparing')->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::where('status', 'delivered')->sum('total_amount'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'pending_orders' => Order::whereIn('status', ['pending', 'confirmed'])->count(),
            'cod_orders' => Order::where('payment_method', 'cod')->count(),
            'bank_transfer_orders' => Order::where('payment_method', 'bank_transfer')->count(),
        ];

        return view('admin.orders.statistics', compact('stats'));
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = Order::with(['orderItems.product']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->get();

        $filename = 'orders_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Headers
            fputcsv($file, [
                'Mã đơn hàng',
                'Tên khách hàng',
                'Email',
                'Số điện thoại',
                'Địa chỉ',
                'Tổng tiền',
                'Trạng thái',
                'Phương thức thanh toán',
                'Ngày tạo',
                'Ghi chú'
            ]);

            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone,
                    $order->customer_address,
                    $order->total_amount,
                    $order->status_name,
                    $order->payment_method ?? 'COD',
                    $order->created_at->format('d/m/Y H:i'),
                    $order->notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Print order invoice
     */
    public function printInvoice(Order $order)
    {
        $order->load(['orderItems.product.category']);
        return view('admin.orders.invoice', compact('order'));
    }
}
