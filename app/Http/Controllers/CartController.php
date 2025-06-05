<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function show_cart(Request $request)
    {
        $category_product = DB::table('categories')->orderBy('id', 'desc')->get();
        $cart = session()->get('cart', []);

        // Lấy danh sách sản phẩm đang active có trong giỏ hàng
        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->where('is_active', 1)->get();

        // Tính tổng tiền
        $totalPrice = 0;
        foreach ($cart as $item) {
            $totalPrice += $item['quantity'] * $item['product_price'];
        }

        return view('pages.cart.show_cart')
            ->with('category_product', $category_product)
            ->with('cart', $cart)
            ->with('products', $products)
            ->with('totalPrice', $totalPrice);
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add_to_cart(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        // Kiểm tra sản phẩm
        $product = Product::find($productId);
        if (!$product || !$product->is_active || $product->stock_quantity < $quantity) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không khả dụng hoặc không đủ tồn kho.',
                ], 400);
            }
            return redirect()->back()->with('error', 'Sản phẩm không khả dụng hoặc không đủ tồn kho.');
        }

        // Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);

        // Thêm hoặc cập nhật sản phẩm trong giỏ hàng
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'product_image' => $product->image_url,
                'quantity' => $quantity
            ];
        }

        // Lưu giỏ hàng vào session
        session()->put('cart', $cart);

        $totalPrice = array_sum(array_map(function ($item) {
            return $item['quantity'] * $item['product_price'];
        }, $cart));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
                'cartCount' => count($cart),
                'cartHtml' => view('pages.cart.overlay', compact('cart', 'totalPrice'))->render(), // Sửa đường dẫn
            ]);
        }

        return redirect()->route('cart.show_cart')->with('success', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    // Cập nhật số lượng sản phẩm
    public function update_cart(Request $request)
    {
        $productId = $request->input('product_id');
        $action = $request->input('action');
        $cart = session()->get('cart', []);

        if (!isset($cart[$productId])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không có trong giỏ hàng.',
                ], 400);
            }
            return redirect()->route('cart.show_cart')->with('error', 'Sản phẩm không có trong giỏ hàng.');
        }

        // Kiểm tra tồn kho
        $product = Product::find($productId);
        if (!$product || !$product->is_active) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm không tồn tại hoặc không khả dụng.',
                ], 400);
            }
            return redirect()->route('cart.show_cart')->with('error', 'Sản phẩm không tồn tại hoặc không khả dụng.');
        }

        if ($action == 'increase') {
            if ($cart[$productId]['quantity'] + 1 > $product->stock_quantity) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Số lượng vượt quá tồn kho.',
                    ], 400);
                }
                return redirect()->route('cart.show_cart')->with('error', 'Số lượng vượt quá tồn kho.');
            }
            $cart[$productId]['quantity']++;
        } elseif ($action == 'decrease') {
            $cart[$productId]['quantity']--;
            if ($cart[$productId]['quantity'] <= 0) {
                unset($cart[$productId]);
            }
        }

        session()->put('cart', $cart);

        $totalPrice = array_sum(array_map(function ($item) {
            return $item['quantity'] * $item['product_price'];
        }, $cart));

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật giỏ hàng thành công!',
                'cartCount' => count($cart),
                'cartHtml' => view('pages.cart.overlay', compact('cart', 'totalPrice'))->render(), // Sửa đường dẫn
            ]);
        }

        return redirect()->route('cart.show_cart')->with('success', 'Cập nhật giỏ hàng thành công.');
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function delete_from_cart(Request $request, $productId)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$productId])) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không có trong giỏ hàng.',
            ], 400);
        }

        unset($cart[$productId]);
        session()->put('cart', $cart);
        $totalPrice = array_sum(array_map(fn ($item) => $item['quantity'] * $item['product_price'], $cart));

        return response()->json([
            'success' => true,
            'message' => 'Xóa sản phẩm thành công!',
            'cartCount' => count($cart),
            'cartHtml' => view('pages.cart.overlay', ['cart' => $cart, 'totalPrice' => $totalPrice])->render(),
        ]);
    }

    // Trả về nội dung overlay giỏ hàng
    public function overlay()
    {
        $cart = session()->get('cart', []);
        $totalPrice = array_sum(array_map(function ($item) {
            return $item['quantity'] * $item['product_price'];
        }, $cart));

        return response()->json([
            'success' => true,
            'cartHtml' => view('cart.overlay', compact('cart', 'totalPrice'))->render(),
            'cartCount' => count($cart),
        ]);
    }
}
