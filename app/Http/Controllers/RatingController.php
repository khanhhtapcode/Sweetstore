<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Thêm dòng này
use App\Models\Rating;


class RatingController extends Controller
{


public function store(Request $request)
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);


    // Kiểm tra user đã đánh giá sản phẩm này chưa
    $exists = Rating::where('product_id', $validated['product_id'])
        ->where('user_id', Auth::id())
        ->exists();


    if ($exists) {
        return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi.');
    }


    try {
        $rating = Rating::create([
            'product_id' => $validated['product_id'],
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);
        return redirect()->back()->with('success', 'Đánh giá của bạn đã được lưu.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Có lỗi khi lưu đánh giá.');
    }
}
}
