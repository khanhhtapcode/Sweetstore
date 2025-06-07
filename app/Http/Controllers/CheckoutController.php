<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    /**
     * Hiển thị trang thanh toán
     */
    public function show()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán.');
        }

        $cartItems = CartItem::where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.show_cart')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        // Kiểm tra tình trạng sản phẩm trong giỏ hàng
        $unavailableItems = [];
        foreach ($cartItems as $item) {
            $product = Product::find($item->product_id);
            if (!$product || !$product->is_active || $product->stock_quantity < $item->quantity) {
                $unavailableItems[] = $item;
            }
        }

        if (!empty($unavailableItems)) {
            // Xóa các sản phẩm không khả dụng khỏi giỏ hàng
            foreach ($unavailableItems as $item) {
                $item->delete();
            }
            return redirect()->route('cart.show_cart')
                ->with('error', 'Một số sản phẩm trong giỏ hàng không còn khả dụng và đã được xóa.');
        }

        // Tính toán tổng tiền
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $shipping = $subtotal >= 500000 ? 0 : 30000; // Miễn phí ship từ 500k
        $total = $subtotal + $shipping;

        return view('pages.checkout.index', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    /**
     * Xử lý thanh toán
     */
    public function process(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán.');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:1000',
            'payment_method' => 'required|in:cod,bank_transfer,credit_card,momo,VNpay', // Thêm momo vào đây
            'notes' => 'nullable|string|max:1000'
        ], [
            'customer_name.required' => 'Vui lòng nhập họ tên.',
            'customer_email.required' => 'Vui lòng nhập email.',
            'customer_email.email' => 'Email không hợp lệ.',
            'customer_phone.required' => 'Vui lòng nhập số điện thoại.',
            'customer_address.required' => 'Vui lòng nhập địa chỉ giao hàng.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ.' // Đây là message lỗi bạn thấy
        ]);


        $cartItems = CartItem::where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.show_cart')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        DB::beginTransaction();

        try {
            // Kiểm tra lại tồn kho và tính tổng tiền
            $subtotal = 0;
            $orderItems = [];

            foreach ($cartItems as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);

                if (!$product || !$product->is_active) {
                    throw new \Exception("Sản phẩm '{$item->product_name}' không còn khả dụng.");
                }

                if ($product->stock_quantity < $item->quantity) {
                    throw new \Exception("Sản phẩm '{$product->name}' chỉ còn {$product->stock_quantity} trong kho.");
                }

                $itemTotal = $item->quantity * $item->price;
                $subtotal += $itemTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ];
            }

            $shipping = $subtotal >= 500000 ? 0 : 30000;
            $total = $subtotal + $shipping;

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'total_amount' => $total,
                'status' => Order::STATUS_PENDING,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes
            ]);

            // Tạo order items và cập nhật tồn kho
            foreach ($orderItems as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price']
                ]);

                // Giảm tồn kho (pending order vẫn giữ tồn kho, sẽ trừ khi confirm)
                // Nếu muốn trừ luôn thì bỏ comment dòng dưới
                // $product = Product::find($itemData['product_id']);
                // $product->decrement('stock_quantity', $itemData['quantity']);
            }

            // Xóa giỏ hàng
            CartItem::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('checkout.success', $order->id)
                ->with('success', 'Đơn hàng đã được tạo thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Trang thành công
     */
    public function success($orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::with(['orderItems.product', 'user'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('orders.history')
                ->with('error', 'Không tìm thấy đơn hàng.');
        }

        return view('pages.checkout.success', compact('order'));
    }

    /**
     * Xem chi tiết đơn hàng
     */
    public function orderDetail($orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::with(['orderItems.product.category', 'user'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('orders.history')
                ->with('error', 'Không tìm thấy đơn hàng.');
        }

        return view('pages.orders.detail', compact('order'));
    }

    /**
     * Danh sách đơn hàng của user
     */
    public function orderHistory(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $query = Order::with(['orderItems.product'])
            ->where('user_id', Auth::id());

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by order number
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('pages.orders.history', compact('orders'));
    }

    /**
     * Hủy đơn hàng
     */
    public function cancelOrder(Request $request, $orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập.');
        }

        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Không thể hủy đơn hàng này.');
        }

        DB::beginTransaction();
        try {
            // Hoàn lại tồn kho nếu đã trừ
            foreach ($order->orderItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    // Chỉ hoàn lại nếu đơn hàng đã được confirm và đã trừ kho
                    // Vì pending order chưa trừ kho nên không cần hoàn lại
                    // $product->increment('stock_quantity', $item->quantity);
                }
            }

            // Cập nhật trạng thái đơn hàng
            $order->update(['status' => 'cancelled']);

            DB::commit();
            return redirect()->back()->with('success', 'Đã hủy đơn hàng thành công.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi hủy đơn hàng.');
        }
    }

    /**
     * Reorder - Đặt lại đơn hàng
     */
    public function reorder($orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::with('orderItems.product')
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('orders.history')
                ->with('error', 'Không tìm thấy đơn hàng.');
        }

        DB::beginTransaction();
        try {
            // Xóa giỏ hàng hiện tại
            CartItem::where('user_id', Auth::id())->delete();

            $addedItems = 0;
            $unavailableItems = [];

            // Thêm lại các sản phẩm vào giỏ hàng
            foreach ($order->orderItems as $item) {
                $product = $item->product;

                if (!$product || !$product->is_active) {
                    $unavailableItems[] = $item->product ? $item->product->name : 'Sản phẩm đã xóa';
                    continue;
                }

                if ($product->stock_quantity < $item->quantity) {
                    $unavailableItems[] = $product->name . " (chỉ còn {$product->stock_quantity})";
                    continue;
                }

                CartItem::create([
                    'user_id' => Auth::id(),
                    'session_id' => \Illuminate\Support\Str::uuid()->toString(),
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                    'price' => $product->price, // Sử dụng giá hiện tại
                    'image_url' => $product->image_url,
                    'product_name' => $product->name,
                ]);

                $addedItems++;
            }

            DB::commit();

            $message = "Đã thêm {$addedItems} sản phẩm vào giỏ hàng.";
            if (!empty($unavailableItems)) {
                $message .= " Một số sản phẩm không khả dụng: " . implode(', ', $unavailableItems);
            }

            return redirect()->route('cart.show_cart')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi đặt lại đơn hàng.');
        }
    }

    /**
     * Track order - Theo dõi đơn hàng
     */
    public function trackOrder(Request $request)
    {
        $order = null;

        if ($request->filled('order_number')) {
            $query = Order::where('order_number', $request->order_number);

            // Nếu user đã đăng nhập, chỉ tìm đơn hàng của họ
            if (Auth::check()) {
                $query->where('user_id', Auth::id());
            }

            $order = $query->with(['orderItems.product'])->first();
        }

        return view('pages.orders.track', compact('order'));
    }

    /**
     * Download invoice PDF
     */
    public function downloadInvoice($orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::with(['orderItems.product.category', 'user'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Tạo PDF và download
        // Cần cài đặt package như dompdf hoặc tcpdf
        return view('pages.orders.invoice', compact('order'));
    }

    /**
     * Review order - Đánh giá đơn hàng
     */
    public function reviewOrder($orderId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::with(['orderItems.product', 'user'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->where('status', 'delivered')
            ->first();

        if (!$order) {
            return redirect()->route('orders.history')
                ->with('error', 'Không thể đánh giá đơn hàng này.');
        }

        return view('pages.orders.review', compact('order'));
    }

    /**
     * Apply coupon
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50'
        ]);

        // Logic áp dụng mã giảm giá
        // Tạo model Coupon và xử lý logic ở đây

        return response()->json([
            'success' => false,
            'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'
        ]);
    }

    /**
     * Calculate shipping fee
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'district' => 'required|string'
        ]);

        // Logic tính phí ship theo địa chỉ
        $shippingFee = 30000; // Mặc định 30k

        // Nếu trong nội thành hoặc đơn hàng > 500k thì miễn phí
        $cartTotal = CartItem::where('user_id', Auth::id())
            ->sum(DB::raw('quantity * price'));

        if ($cartTotal >= 500000) {
            $shippingFee = 0;
        }

        return response()->json([
            'success' => true,
            'shipping_fee' => $shippingFee,
            'formatted_fee' => $shippingFee == 0 ? 'Miễn phí' : number_format($shippingFee) . 'đ'
        ]);
    }
}
