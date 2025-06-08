<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class VerifyEmailController extends Controller
{
    /**
     * Verify email without session dependency
     */
    public function __invoke(Request $request): RedirectResponse
    {
        try {
            Log::info('=== INDEPENDENT EMAIL VERIFICATION ===');
            Log::info('Full URL: ' . $request->fullUrl());

            $userId = $request->route('id');
            $hash = $request->route('hash');
            $expires = $request->query('expires');
            $signature = $request->query('signature');

            Log::info('Verification data: ', [
                'user_id' => $userId,
                'hash' => $hash,
                'expires' => $expires,
                'signature' => $signature,
                'current_time' => time()
            ]);

            // 1. Kiểm tra user tồn tại
            $user = User::find($userId);
            if (!$user) {
                Log::error('User not found: ' . $userId);
                return $this->failedVerification('Người dùng không tồn tại!');
            }

            // 2. Kiểm tra đã verify chưa
            if ($user->hasVerifiedEmail()) {
                Log::info('User already verified');
                return $this->successfulLogin($user, 'Email đã được xác thực trước đó!');
            }

            // 3. Kiểm tra link đã hết hạn chưa
            if ($expires && time() > $expires) {
                Log::warning('Link expired');
                return $this->failedVerification('Link xác thực đã hết hạn! Vui lòng yêu cầu gửi lại.');
            }

            // 4. Verify hash và signature MANUAL (không dùng middleware signed)
            if (!$this->verifySignature($request, $user)) {
                Log::warning('Invalid signature or hash');
                return $this->failedVerification('Link xác thực không hợp lệ!');
            }

            // 5. Mark email as verified
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
                Log::info('Email verified successfully for user: ' . $user->id);

                return $this->successfulLogin($user, 'Email đã được xác thực thành công! Chào mừng bạn! 🎉');
            }

            return $this->failedVerification('Không thể xác thực email. Vui lòng thử lại!');

        } catch (\Exception $e) {
            Log::error('Email verification exception: ', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return $this->failedVerification('Có lỗi xảy ra khi xác thực email!');
        }
    }

    /**
     * Verify signature manually without session dependency
     */
    private function verifySignature(Request $request, User $user): bool
    {
        try {
            $hash = $request->route('hash');
            $expectedHash = sha1($user->getEmailForVerification());

            // Kiểm tra hash trước
            if (!hash_equals($expectedHash, $hash)) {
                Log::warning('Hash mismatch', [
                    'expected' => $expectedHash,
                    'provided' => $hash,
                    'email' => $user->getEmailForVerification()
                ]);
                return false;
            }

            // Tạo lại URL để verify signature
            $url = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::createFromTimestamp($request->query('expires', time() + 3600)),
                [
                    'id' => $user->getKey(),
                    'hash' => $hash,
                ]
            );

            // Lấy signature từ URL được tạo
            $urlParts = parse_url($url);
            parse_str($urlParts['query'], $expectedParams);
            $expectedSignature = $expectedParams['signature'] ?? '';

            $providedSignature = $request->query('signature');

            Log::info('Signature verification: ', [
                'expected_signature' => $expectedSignature,
                'provided_signature' => $providedSignature,
                'match' => hash_equals($expectedSignature, $providedSignature)
            ]);

            return hash_equals($expectedSignature, $providedSignature);

        } catch (\Exception $e) {
            Log::error('Signature verification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Handle successful verification and login
     */
    private function successfulLogin(User $user, string $message): RedirectResponse
    {
        // Đăng nhập user
        Auth::login($user);
        $user->updateLastLogin();

        // Redirect theo role
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('verified', $message);
        }

        return redirect()->route('dashboard')->with('verified', $message);
    }

    /**
     * Handle failed verification
     */
    private function failedVerification(string $message): RedirectResponse
    {
        return redirect()->route('login')->with('error', $message);
    }
}
