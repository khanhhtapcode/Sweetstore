<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function show_cart(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng.');
        }

        $category_product = DB::table('categories')->orderBy('id', 'desc')->get();
        
        // Lấy danh sách sản phẩm trong giỏ hàng của người dùng
        $cartItems = CartItem::where('user_id', Auth::id())->get();
        
        // Lấy danh sách sản phẩm đang active có trong giỏ hàng
        $productIds = $cartItems->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $productIds)->where('is_active', 1)->get();
        
        // Tính tổng tiền
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return view('pages.cart.show_cart', compact('category_product', 'cartItems', 'products', 'totalPrice'));
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add_to_cart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!',
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không khả dụng!',
            ], 400);
        }

        if (($product->stock_quantity ?? 0) < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng yêu cầu vượt quá tồn kho!',
            ], 400);
        }

        // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
        $cartItem = CartItem::where('user_id', Auth::id())
                            ->where('product_id', $product->id)
                            ->first();

        if ($cartItem) {
            // Cập nhật số lượng nếu sản phẩm đã có trong giỏ
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            // Thêm sản phẩm mới vào giỏ
            CartItem::create([
                'user_id' => Auth::id(),
                'session_id' => Str::uuid()->toString(), // Tạo session_id ngẫu nhiên
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'image_url' => $product->image_url,
                'product_name' => $product->name,
            ]);
        }

        $cartItems = CartItem::where('user_id', Auth::id())->get();
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $cartHtml = view('pages.cart.overlay', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice
        ])->render();

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng!',
            'cartCount' => $cartItems->count(),
            'cartHtml' => $cartHtml,
        ]);
    }

    // Cập nhật số lượng sản phẩm
    public function update_cart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để cập nhật giỏ hàng!',
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'action' => 'required|in:increase,decrease',
        ]);

        $productId = $request->input('product_id');
        $action = $request->input('action');

        $cartItem = CartItem::where('user_id', Auth::id())
                            ->where('product_id', $productId)
                            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không có trong giỏ hàng.',
            ], 400);
        }

        $product = Product::find($productId);
        if (!$product || !$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại hoặc không khả dụng.',
            ], 400);
        }

        if ($action === 'increase') {
            if (($cartItem->quantity + 1) > ($product->stock_quantity ?? 0)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng vượt quá tồn kho.',
                ], 400);
            }
            $cartItem->quantity++;
        } elseif ($action === 'decrease') {
            $cartItem->quantity--;
            if ($cartItem->quantity <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->save();
            }
        }

        if ($cartItem->exists) {
            $cartItem->save();
        }

        $cartItems = CartItem::where('user_id', Auth::id())->get();
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật giỏ hàng thành công!',
            'cartCount' => $cartItems->count(),
            'cartHtml' => view('pages.cart.overlay', compact('cartItems', 'totalPrice'))->render(),
        ]);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function delete_from_cart(Request $request, $productId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để xóa sản phẩm khỏi giỏ hàng!',
            ], 401);
        }

        $cartItem = CartItem::where('user_id', Auth::id())
                            ->where('product_id', $productId)
                            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không có trong giỏ hàng.',
            ], 400);
        }

        $cartItem->delete();

        $cartItems = CartItem::where('user_id', Auth::id())->get();
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return response()->json([
            'success' => true,
            'message' => 'Xóa sản phẩm thành công!',
            'cartCount' => $cartItems->count(),
            'cartHtml' => view('pages.cart.overlay', ['cartItems' => $cartItems, 'totalPrice' => $totalPrice])->render(),
        ]);
    }

    // Trả về nội dung overlay giỏ hàng
    public function overlay()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để xem giỏ hàng!',
            ], 401);
        }

        $cartItems = CartItem::where('user_id', Auth::id())->get();
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return response()->json([
            'success' => true,
            'cartHtml' => view('pages.cart.overlay', compact('cartItems', 'totalPrice'))->render(),
            'cartCount' => $cartItems->count(),
        ]);
    }
}