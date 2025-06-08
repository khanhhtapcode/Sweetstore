<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        try {
            $user = $request->user();

            // Log để debug
            Log::info('Email verification attempt', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'request_id' => $request->route('id'),
                'request_hash' => $request->route('hash'),
                'expected_hash' => sha1($user->getEmailForVerification())
            ]);

            // Kiểm tra user đã verify chưa
            if ($user->hasVerifiedEmail()) {
                Log::info('User already verified', ['user_id' => $user->id]);
                return redirect()->intended(route('dashboard', absolute: false))->with('verified', 'Email đã được xác thực!');
            }

            // Kiểm tra ID khớp không
            if ($request->route('id') != $user->id) {
                Log::warning('User ID mismatch', [
                    'request_id' => $request->route('id'),
                    'user_id' => $user->id
                ]);
                abort(403, 'Invalid verification link');
            }

            // Kiểm tra hash
            $expectedHash = sha1($user->getEmailForVerification());
            $providedHash = $request->route('hash');

            if (!hash_equals($expectedHash, $providedHash)) {
                Log::warning('Hash mismatch', [
                    'expected' => $expectedHash,
                    'provided' => $providedHash
                ]);
                abort(403, 'Invalid verification hash');
            }

            // Mark email as verified
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
                Log::info('Email verified successfully', ['user_id' => $user->id]);
            }

            return redirect()->intended(route('dashboard', absolute: false))->with('verified', 'Email đã được xác thực thành công! 🎉');

        } catch (\Exception $e) {
            Log::error('Email verification failed', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()?->id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('verification.notice')->with('error', 'Có lỗi xảy ra khi xác thực email. Vui lòng thử lại!');
        }
    }
}
