<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product', 'driver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by driver
        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
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

        // Get drivers for filter dropdown
        $drivers = Driver::select('id', 'name', 'driver_code')->get();

        return view('admin.orders.index', compact('orders', 'drivers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::active()->orderBy('name')->get();
        $drivers = Driver::available()->get();
        return view('admin.orders.create', compact('products', 'drivers'));
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
            'payment_method' => 'nullable|in:cod,bank_transfer,credit_card','vnpay',
            'driver_id' => 'nullable|exists:drivers,id',
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
                'driver_id' => $request->driver_id,
                'notes' => $request->notes
            ]);

            // Tạo order items và cập nhật tồn kho
            $totalWeight = 0;
            $totalVolume = 0;
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

                // Tính toán trọng lượng và thể tích (giả định)
                $totalWeight += ($product->weight ?? 0.5) * $productData['quantity']; // kg
                $totalVolume += ($product->volume ?? 0.01) * $productData['quantity']; // m³

                // Trừ tồn kho nếu đơn hàng không phải pending hoặc cancelled
                if (!in_array($order->status, ['pending', 'cancelled'])) {
                    $product->decrement('stock_quantity', $productData['quantity']);
                }
            }

            // Tự động gán tài xế nếu đơn hàng sẵn sàng giao và chưa có tài xế
            if ($order->status === 'ready' && !$order->driver_id) {
                $suggestedDriver = $this->findBestDriver($totalWeight, $totalVolume, $order->customer_address);
                if ($suggestedDriver) {
                    $order->assignDriver($suggestedDriver);
                }
            } elseif ($order->driver_id && in_array($order->status, ['ready', 'assigned'])) {
                // Nếu đã chọn tài xế, gán luôn
                $driver = Driver::find($order->driver_id);
                if ($driver && $driver->canTakeNewOrder()) {
                    $order->assignDriver($driver);
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
        $order->load(['user', 'orderItems.product.category', 'driver']);
        $availableDrivers = Driver::available()->get();
        return view('admin.orders.show', compact('order', 'availableDrivers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $products = Product::orderBy('name')->get();
        $drivers = Driver::available()->get();
        $order->load('orderItems.product');
        return view('admin.orders.edit', compact('order', 'products', 'drivers'));
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
            'driver_id' => 'nullable|exists:drivers,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        DB::beginTransaction();
        try {
            $oldStatus = $order->status;
            $oldDriverId = $order->driver_id;

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
                'driver_id' => $request->driver_id,
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

            // Xử lý thay đổi tài xế
            if ($oldDriverId != $request->driver_id) {
                // Hủy gán tài xế cũ
                if ($oldDriverId) {
                    $oldDriver = Driver::find($oldDriverId);
                    if ($oldDriver && $oldDriver->currentOrders()->count() <= 1) {
                        $oldDriver->markAsAvailable();
                    }
                }

                // Gán tài xế mới
                if ($request->driver_id && in_array($request->status, ['ready', 'assigned'])) {
                    $newDriver = Driver::find($request->driver_id);
                    if ($newDriver && $newDriver->canTakeNewOrder()) {
                        $order->assignDriver($newDriver);
                    }
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

            // Cập nhật trạng thái tài xế nếu có
            if ($order->driver_id) {
                $driver = Driver::find($order->driver_id);
                if ($driver && $driver->currentOrders()->count() <= 1) {
                    $driver->markAsAvailable();
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

            // Kiểm tra logic chuyển trạng thái hợp lệ
            if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
                throw new \Exception("Không thể chuyển từ trạng thái '{$oldStatus}' sang '{$newStatus}'");
            }

            // Xử lý tồn kho dựa trên chuyển đổi trạng thái
            $this->handleStockForStatusChange($order, $oldStatus, $newStatus);

            // Xử lý tài xế dựa trên trạng thái mới
            $this->handleDriverForStatusChange($order, $oldStatus, $newStatus);

            // Cập nhật trạng thái
            $order->update(['status' => $newStatus]);

            DB::commit();

            $oldStatusName = Order::getStatuses()[$oldStatus] ?? $oldStatus;
            $newStatusName = Order::getStatuses()[$newStatus] ?? $newStatus;

            return response()->json([
                'success' => true,
                'message' => "Đơn hàng {$order->order_number} đã được chuyển từ '{$oldStatusName}' sang '{$newStatusName}'!"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }


    /**
     * Assign driver to order
     */
    public function assignDriver(Request $request, Order $order)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id'
        ]);

        $driver = Driver::findOrFail($request->driver_id);

        // Kiểm tra điều kiện
        if (!$driver->canTakeNewOrder()) {
            return response()->json([
                'success' => false,
                'message' => 'Tài xế không thể nhận thêm đơn hàng.'
            ], 422);
        }

        if (!in_array($order->status, ['ready', 'assigned'])) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng không thể gán tài xế ở trạng thái hiện tại.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Hủy gán tài xế cũ nếu có
            if ($order->driver_id && $order->driver_id != $driver->id) {
                $oldDriver = Driver::find($order->driver_id);
                if ($oldDriver && $oldDriver->currentOrders()->where('id', '!=', $order->id)->count() === 0) {
                    $oldDriver->markAsAvailable();
                }
            }

            // Gán tài xế mới
            $order->update([
                'driver_id' => $driver->id,
                'status' => 'assigned',
                'assigned_at' => now()
            ]);

            $driver->markAsBusy();

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Đã gán tài xế {$driver->name} cho đơn hàng #{$order->order_number}"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 422);
        }
    }
    private function isValidStatusTransition($from, $to)
    {
        // Định nghĩa các chuyển đổi hợp lệ
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['preparing', 'cancelled'],
            'preparing' => ['ready', 'cancelled'],
            'ready' => ['assigned', 'cancelled'],
            'assigned' => ['picked_up', 'cancelled', 'ready'], // Có thể quay lại ready khi hủy gán tài xế
            'picked_up' => ['delivering', 'cancelled'],
            'delivering' => ['delivered', 'cancelled'],
            'delivered' => [], // Không thể chuyển từ delivered
            'cancelled' => ['pending'] // Có thể khôi phục đơn đã hủy
        ];

        return in_array($to, $validTransitions[$from] ?? []);
    }
    private function handleStockForStatusChange($order, $oldStatus, $newStatus)
    {
        // Các trạng thái cần trừ tồn kho
        $stockReducedStatuses = ['confirmed', 'preparing', 'ready', 'assigned', 'picked_up', 'delivering', 'delivered'];
        // Các trạng thái không trừ tồn kho
        $stockNotReducedStatuses = ['pending', 'cancelled'];

        $wasStockReduced = in_array($oldStatus, $stockReducedStatuses);
        $shouldReduceStock = in_array($newStatus, $stockReducedStatuses);

        if (!$wasStockReduced && $shouldReduceStock) {
            // Trừ tồn kho khi chuyển từ trạng thái không trừ sang trạng thái cần trừ
            $this->reduceStock($order);
        } elseif ($wasStockReduced && !$shouldReduceStock) {
            // Hoàn lại tồn kho khi chuyển từ trạng thái đã trừ sang trạng thái không trừ
            $this->restoreStock($order);
        }
    }
    private function handleDriverForStatusChange($order, $oldStatus, $newStatus)
    {
        // Tự động gán tài xế khi chuyển sang "ready" và chưa có tài xế
        if ($newStatus === 'ready' && !$order->driver_id) {
            $suggestedDriver = $this->findBestDriverForOrder($order);
            if ($suggestedDriver) {
                $order->driver_id = $suggestedDriver->id;
                $order->assigned_at = now();
                $newStatus = 'assigned'; // Tự động chuyển sang assigned
                $suggestedDriver->markAsBusy();
            }
        }

        // Xử lý khi chuyển sang assigned
        if ($newStatus === 'assigned' && $order->driver_id) {
            $driver = Driver::find($order->driver_id);
            if ($driver && $driver->canTakeNewOrder()) {
                $driver->markAsBusy();
                $order->assigned_at = now();
            }
        }

        // Xử lý khi hoàn thành hoặc hủy
        if (in_array($newStatus, ['delivered', 'cancelled']) && $order->driver_id) {
            $driver = Driver::find($order->driver_id);
            if ($driver) {
                if ($newStatus === 'delivered') {
                    $driver->increment('total_deliveries');
                    $driver->updateRating();
                    $order->delivered_at = now();
                }

                // Kiểm tra nếu không còn đơn nào đang giao
                $remainingOrders = $driver->currentOrders()
                    ->where('id', '!=', $order->id)
                    ->count();

                if ($remainingOrders === 0) {
                    $driver->markAsAvailable();
                }
            }
        }

        // Xử lý khi quay lại ready từ assigned (hủy gán tài xế)
        if ($oldStatus === 'assigned' && $newStatus === 'ready') {
            if ($order->driver_id) {
                $driver = Driver::find($order->driver_id);
                $order->driver_id = null;
                $order->assigned_at = null;

                if ($driver && $driver->currentOrders()->where('id', '!=', $order->id)->count() === 0) {
                    $driver->markAsAvailable();
                }
            }
        }
    }

    /**
     * Update delivery status - FIXED VERSION với logging
     */
    public function updateDeliveryStatus(Request $request, Order $order)
    {
        // Debug logging
        \Log::info('updateDeliveryStatus called', [
            'order_id' => $order->id,
            'current_status' => $order->status,
            'requested_status' => $request->status,
            'driver_id' => $order->driver_id
        ]);

        $request->validate([
            'status' => 'required|in:picked_up,delivering,delivered',
            'notes' => 'nullable|string|max:500'
        ]);

        // FIXED: Mở rộng danh sách trạng thái được phép
        $allowedCurrentStatuses = ['ready', 'assigned', 'picked_up', 'delivering'];

        if (!in_array($order->status, $allowedCurrentStatuses)) {
            \Log::warning('Invalid current status for delivery update', [
                'order_id' => $order->id,
                'current_status' => $order->status,
                'allowed_statuses' => $allowedCurrentStatuses
            ]);

            return response()->json([
                'success' => false,
                'message' => "Không thể cập nhật trạng thái giao hàng từ '{$order->status}'. Trạng thái hiện tại: {$order->status_name}"
            ], 422);
        }

        // FIXED: Kiểm tra logic chuyển đổi hợp lệ
        $requestedStatus = $request->status;
        $validTransitions = [
            'ready' => ['picked_up'],
            'assigned' => ['picked_up'],
            'picked_up' => ['delivering', 'delivered'],
            'delivering' => ['delivered']
        ];

        if (!in_array($requestedStatus, $validTransitions[$order->status] ?? [])) {
            return response()->json([
                'success' => false,
                'message' => "Không thể chuyển từ '{$order->status_name}' sang '{$requestedStatus}'"
            ], 422);
        }

        DB::beginTransaction();
        try {
            $notes = $request->notes;

            switch ($requestedStatus) {
                case 'picked_up':
                    $order->update([
                        'status' => 'picked_up',
                        'picked_up_at' => now(),
                        'delivery_notes' => $notes
                    ]);
                    $message = 'Đã cập nhật: Đã lấy hàng';
                    break;

                case 'delivering':
                    $order->update([
                        'status' => 'delivering',
                        'delivery_notes' => $notes
                    ]);
                    $message = 'Đã cập nhật: Đang giao hàng';
                    break;

                case 'delivered':
                    $order->update([
                        'status' => 'delivered',
                        'delivered_at' => now(),
                        'delivery_notes' => $notes
                    ]);

                    // Cập nhật thống kê tài xế
                    if ($order->driver) {
                        $order->driver->increment('total_deliveries');
                        $order->driver->updateRating();

                        // Kiểm tra xem còn đơn nào đang giao không
                        if ($order->driver->currentOrders()->where('id', '!=', $order->id)->count() === 0) {
                            $order->driver->markAsAvailable();
                        }
                    }
                    $message = 'Đã cập nhật: Giao hàng thành công';
                    break;

                default:
                    throw new \Exception('Trạng thái không hợp lệ');
            }

            \Log::info('Delivery status updated successfully', [
                'order_id' => $order->id,
                'old_status' => $order->getOriginal('status'),
                'new_status' => $order->status
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            \Log::error('Error updating delivery status', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 422);
        }
    }
    /**
     * Trừ tồn kho
     */
    private function reduceStock($order)
    {
        foreach ($order->orderItems as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                if ($product->stock_quantity >= $item->quantity) {
                    $product->decrement('stock_quantity', $item->quantity);
                } else {
                    throw new \Exception("Sản phẩm '{$product->name}' không đủ tồn kho. Còn lại: {$product->stock_quantity}, cần: {$item->quantity}");
                }
            }
        }
    }

    /**
     * Hoàn lại tồn kho
     */
    private function restoreStock($order)
    {
        foreach ($order->orderItems as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('stock_quantity', $item->quantity);
            }
        }
    }
    /**
     * Unassign driver from order
     */
    public function unassignDriver(Order $order)
    {
        if (!$order->driver_id) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng chưa được gán tài xế.'
            ], 422);
        }

        if (!in_array($order->status, ['assigned', 'picked_up'])) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể hủy gán tài xế ở trạng thái hiện tại.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $driverName = $order->driver->name;
            $driver = $order->driver;

            $order->update([
                'driver_id' => null,
                'status' => 'ready',
                'assigned_at' => null,
                'picked_up_at' => null,
                'delivery_notes' => null
            ]);

            // Cập nhật trạng thái tài xế nếu không còn đơn nào
            if ($driver && $driver->currentOrders()->count() === 0) {
                $driver->markAsAvailable();
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Đã hủy gán tài xế {$driverName} cho đơn hàng #{$order->order_number}"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 422);
        }
    }
    public function autoAssignDrivers()
    {
        DB::beginTransaction();
        try {
            $readyOrders = Order::where('status', 'ready')
                ->whereNull('driver_id')
                ->with('orderItems.product')
                ->get();

            $assignedCount = 0;

            foreach ($readyOrders as $order) {
                $driver = $this->findBestDriverForOrder($order);
                if ($driver) {
                    $order->update([
                        'driver_id' => $driver->id,
                        'status' => 'assigned',
                        'assigned_at' => now()
                    ]);
                    $driver->markAsBusy();
                    $assignedCount++;
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Đã tự động gán tài xế cho {$assignedCount} đơn hàng"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 422);
        }
    }
    private function findBestDriverForOrder($order)
    {
        $totalWeight = $order->orderItems->sum(function($item) {
            return ($item->product->weight ?? 0.5) * $item->quantity;
        });

        $totalVolume = $order->orderItems->sum(function($item) {
            return ($item->product->volume ?? 0.01) * $item->quantity;
        });

        return $this->findBestDriver($totalWeight, $totalVolume, $order->customer_address);
    }
    /**
     * Find the best available driver for an order - FIXED VERSION
     */
    /**
     * Find the best available driver for an order - FIXED VERSION
     */
    /**
     * Find the best available driver for an order - COMPLETELY FIXED
     */
    private function findBestDriver($totalWeight, $totalVolume, $deliveryAddress)
    {
        // Xác định loại xe cần thiết
        $requiredVehicleType = $this->determineRequiredVehicleType($totalWeight, $totalVolume);

        // FIXED: Query đơn giản, tránh hoàn toàn lỗi GROUP BY
        $drivers = Driver::where('status', Driver::STATUS_ACTIVE)
            ->where('vehicle_type', $requiredVehicleType)
            ->get();

        // Lọc tài xế có thể nhận đơn bằng PHP
        $availableDrivers = $drivers->filter(function($driver) {
            return $driver->canTakeNewOrder();
        });

        // Nếu không có tài xế phù hợp, thử loại xe lớn hơn
        if ($availableDrivers->isEmpty()) {
            $fallbackVehicles = ['motorbike', 'small_truck', 'van'];
            $currentIndex = array_search($requiredVehicleType, $fallbackVehicles);

            if ($currentIndex !== false) {
                for ($i = $currentIndex + 1; $i < count($fallbackVehicles); $i++) {
                    $drivers = Driver::where('status', Driver::STATUS_ACTIVE)
                        ->where('vehicle_type', $fallbackVehicles[$i])
                        ->get();

                    $availableDrivers = $drivers->filter(function($driver) {
                        return $driver->canTakeNewOrder();
                    });

                    if ($availableDrivers->isNotEmpty()) {
                        break;
                    }
                }
            }
        }

        if ($availableDrivers->isEmpty()) {
            return null;
        }

        // Sắp xếp theo: số đơn hiện tại (ít hơn) -> rating (cao hơn)
        return $availableDrivers->sortBy(function($driver) {
            $currentOrdersCount = $driver->currentOrders()->count();
            return [$currentOrdersCount, -$driver->rating]; // Âm để sort rating DESC
        })->first();
    }

    /**
     * Determine required vehicle type based on weight and volume
     */
    private function determineRequiredVehicleType($totalWeight, $totalVolume)
    {
        // Logic xác định loại xe dựa trên trọng lượng và thể tích
        if ($totalWeight <= 5 && $totalVolume <= 0.05) { // <= 5kg và <= 0.05m³
            return 'motorbike';
        } elseif ($totalWeight <= 50 && $totalVolume <= 2) { // <= 50kg và <= 2m³
            return 'small_truck';
        } else {
            return 'van';
        }
    }

    /**
     * Get suggested drivers for order
     */
    public function getSuggestedDrivers(Order $order)
    {
        $totalWeight = $order->orderItems->sum(function($item) {
            return ($item->product->weight ?? 0.5) * $item->quantity;
        });

        $totalVolume = $order->orderItems->sum(function($item) {
            return ($item->product->volume ?? 0.01) * $item->quantity;
        });

        $suggestedDriver = $this->findBestDriver($totalWeight, $totalVolume, $order->customer_address);
        $allAvailableDrivers = Driver::available()->withCount('currentOrders')->get();

        return response()->json([
            'suggested_driver' => $suggestedDriver,
            'all_drivers' => $allAvailableDrivers,
            'order_requirements' => [
                'weight' => $totalWeight,
                'volume' => $totalVolume,
                'required_vehicle' => $this->determineRequiredVehicleType($totalWeight, $totalVolume)
            ]
        ]);
    }

    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $query = Order::with(['orderItems.product', 'driver']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
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
                'Tài xế',
                'Xe',
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
                    $order->driver ? $order->driver->name : 'Chưa gán',
                    $order->driver ? $order->driver->vehicle_number : '',
                    $order->created_at->format('d/m/Y H:i'),
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
