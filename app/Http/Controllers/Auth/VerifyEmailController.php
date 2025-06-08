<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     * KHÔNG CẦN AUTH - Xử lý public verification
     */
    public function __invoke(Request $request): RedirectResponse
    {
        try {
            Log::info('=== EMAIL VERIFICATION START ===');
            Log::info('Full URL: ' . $request->fullUrl());

            // Lấy user từ ID trong URL, KHÔNG từ auth
            $userId = $request->route('id');
            $hash = $request->route('hash');

            Log::info('Verification attempt: ', [
                'user_id' => $userId,
                'hash' => $hash
            ]);

            // Tìm user theo ID
            $user = User::find($userId);

            if (!$user) {
                Log::error('User not found: ' . $userId);
                return redirect()->route('login')->with('error', 'Người dùng không tồn tại!');
            }

            Log::info('User found: ', [
                'id' => $user->id,
                'email' => $user->email,
                'is_verified' => $user->hasVerifiedEmail()
            ]);

            // Kiểm tra user đã verify chưa
            if ($user->hasVerifiedEmail()) {
                Log::info('User already verified');

                // Đăng nhập user và redirect
                Auth::login($user);

                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard')->with('verified', 'Email đã được xác thực!');
                }

                return redirect()->route('dashboard')->with('verified', 'Email đã được xác thực!');
            }

            // Verify hash
            $expectedHash = sha1($user->getEmailForVerification());

            if (!hash_equals($expectedHash, $hash)) {
                Log::warning('Hash mismatch: ', [
                    'expected' => $expectedHash,
                    'provided' => $hash
                ]);
                return redirect()->route('login')->with('error', 'Link xác thực không hợp lệ!');
            }

            // Mark email as verified
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
                Log::info('Email verified successfully for user: ' . $user->id);

                // TỰ ĐỘNG ĐĂNG NHẬP USER SAU KHI VERIFY
                Auth::login($user);

                // Update last login
                $user->updateLastLogin();

                // Redirect theo role
                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard')
                        ->with('verified', 'Email đã được xác thực thành công! Chào mừng bạn! 🎉');
                }

                return redirect()->route('dashboard')
                    ->with('verified', 'Email đã được xác thực thành công! Chào mừng bạn! 🎉');
            }

            Log::error('Failed to mark email as verified');
            return redirect()->route('login')->with('error', 'Không thể xác thực email. Vui lòng thử lại!');

        } catch (\Exception $e) {
            Log::error('Email verification exception: ', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return redirect()->route('login')->with('error', 'Có lỗi xảy ra khi xác thực email!');
        }
    }
}
