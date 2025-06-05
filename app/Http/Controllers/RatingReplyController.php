<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\RatingReply;
use Illuminate\Support\Facades\Auth;


class RatingReplyController extends Controller
{
    public function store(Request $request, $ratingId)
    {
        $request->validate([
            'reply' => 'required|string|max:1000',
        ]);


        RatingReply::create([
            'rating_id' => $ratingId,
            'user_id' => Auth::id(),
            'reply' => $request->reply,
        ]);


        return redirect()->back()->with('success', 'Đã trả lời đánh giá.');
    }
}
