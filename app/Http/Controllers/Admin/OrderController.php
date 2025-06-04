<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
        return view('admin.orders.create');
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
            'total_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000'
        ]);

        Order::create([
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'total_amount' => $request->total_amount,
            'status' => Order::STATUS_PENDING,
            'notes' => $request->notes
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đơn hàng đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['user', 'products.category']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('admin.orders.edit', compact('order'));
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
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatuses())),
            'notes' => 'nullable|string|max:1000'
        ]);

        $order->update([
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'total_amount' => $request->total_amount,
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Đơn hàng đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $orderNumber = $order->order_number;
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', "Đơn hàng {$orderNumber} đã được xóa thành công!");
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(Order::getStatuses()))
        ]);

        $oldStatus = $order->status_name;
        $order->update(['status' => $request->status]);
        $newStatus = $order->status_name;

        return redirect()->route('admin.orders.index')
            ->with('success', "Đơn hàng {$order->order_number} đã được chuyển từ '{$oldStatus}' sang '{$newStatus}'!");
    }
}
