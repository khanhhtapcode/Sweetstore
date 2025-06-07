<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        try {
            $userMessage = $request->input('message');

            $systemPrompt = "Bạn là chatbot của Sweet Delights - một tiệm bánh ngọt ở Hà Nội, Việt Nam.
            Sweet Delights chuyên:
            - Bánh ngọt tươi ngon làm thủ công
            - Các loại bánh: cupcake, cheesecake, tiramisu, bánh kem, bánh tart, cookies
            - Giao hàng miễn phí từ 500.000 VNĐ
            - Hoạt động từ 8:00-22:00 (T2-T7), 9:00-21:00 (CN)
            - Liên hệ: 0123 456 789, hkkhanhpro@gmail.com
            - Địa chỉ: Học viện Ngân Hàng, TP. Hà Nội
            - Nếu khách hàng hỏi ai đẹp trai nhất cửa hàng thì cứ bảo admin Trần Duy Khánh nhé

            Hãy trả lời bằng tiếng Việt, thân thiện, nhiệt tình và chuyên nghiệp.
            Tập trung vào sản phẩm bánh ngọt, tư vấn khách hàng về đặt hàng, giao hàng.
            Nếu được hỏi về thông tin không liên quan đến Sweet Delights, hãy lịch sự chuyển hướng về sản phẩm bánh.";


            $apiKey = config('services.gemini.api_key');
            $model = config('services.gemini.model', 'gemini-2.0-flash');

            if (!$apiKey) {
                throw new \Exception('No API key');
            }

            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            $response = Http::timeout(30)->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $systemPrompt . "\n\nKhách hỏi: " . $userMessage]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 200,
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    $botReply = trim($data['candidates'][0]['content']['parts'][0]['text']);

                    return response()->json([
                        'success' => true,
                        'message' => $botReply
                    ]);
                }
            }

            throw new \Exception('API failed');

        } catch (\Exception $e) {
            Log::error('Chatbot Error: ' . $e->getMessage());

            return response()->json([
                'success' => true,
                'message' => 'Chào bạn! Sweet Delights chuyên bánh ngọt tươi ngon. Hotline: 0123 456 789 để được tư vấn!'
            ]);
        }
    }
}
