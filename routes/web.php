<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Route debug đơn giản để test
Route::get('/debug-test', function () {
    return response()->json([
        'message' => 'Debug route works!',
        'time' => now(),
        'app_url' => config('app.url')
    ]);
});

// Route để xem users trong database
Route::get('/debug-users', function () {
    try {
        $users = \App\Models\User::all(['id', 'email', 'email_verified_at', 'created_at']);

        return response()->json([
            'total_users' => $users->count(),
            'users' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'email' => $user->email,
                    'is_verified' => $user->hasVerifiedEmail(),
                    'email_verified_at' => $user->email_verified_at,
                    'created_at' => $user->created_at,
                    'expected_hash' => sha1($user->getEmailForVerification())
                ];
            })
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ]);
    }
});

// Route test verification - KHÔNG middleware
Route::get('/test-verify/{id}/{hash}', function ($id, $hash) {
    \Log::info('=== TEST VERIFY ACCESSED ===', [
        'id' => $id,
        'hash' => $hash,
        'url' => request()->fullUrl()
    ]);

    try {
        $user = \App\Models\User::find($id);

        if (!$user) {
            return response()->json([
                'error' => 'User not found',
                'id' => $id
            ]);
        }

        $expectedHash = sha1($user->getEmailForVerification());

        return response()->json([
            'success' => true,
            'user_id' => $user->id,
            'user_email' => $user->email,
            'is_verified' => $user->hasVerifiedEmail(),
            'expected_hash' => $expectedHash,
            'provided_hash' => $hash,
            'hash_match' => $expectedHash === $hash,
            'email_for_verification' => $user->getEmailForVerification()
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
});

// Route verification thực tế - KHÔNG middleware
Route::get('/verify-email/{id}/{hash}', function (Request $request, $id, $hash) {
    \Log::info('=== VERIFICATION ATTEMPT ===', [
        'id' => $id,
        'hash' => $hash,
        'full_url' => $request->fullUrl()
    ]);

    try {
        $user = \App\Models\User::find($id);

        if (!$user) {
            \Log::error('User not found: ' . $id);
            return redirect('/login')->with('error', 'User not found');
        }

        if ($user->hasVerifiedEmail()) {
            \Log::info('User already verified');
            \Auth::login($user);
            return redirect('/dashboard')->with('verified', 'Already verified!');
        }

        $expectedHash = sha1($user->getEmailForVerification());
        if ($expectedHash !== $hash) {
            \Log::warning('Hash mismatch', [
                'expected' => $expectedHash,
                'provided' => $hash
            ]);
            return redirect('/login')->with('error', 'Invalid hash');
        }

        // Mark as verified và login
        $user->markEmailAsVerified();
        \Auth::login($user);

        \Log::info('Verification successful for user: ' . $user->id);

        return redirect('/dashboard')->with('verified', 'Email verified successfully! 🎉');

    } catch (\Exception $e) {
        \Log::error('Verification error: ' . $e->getMessage());
        return redirect('/login')->with('error', 'Error: ' . $e->getMessage());
    }
});

// Dashboard đơn giản để test redirect
Route::get('/dashboard', function () {
    if (!\Auth::check()) {
        return redirect('/login');
    }

    return '<h1>Dashboard</h1><p>Welcome ' . \Auth::user()->email . '</p><p>Email verified: ' . (\Auth::user()->hasVerifiedEmail() ? 'YES' : 'NO') . '</p>';
})->name('dashboard');

// Login page đơn giản
Route::get('/login', function () {
    return '<h1>Login Page</h1><p>You were redirected to login</p>';
})->name('login');

// Home page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Tạm thời COMMENT OUT auth.php
// require __DIR__.'/auth.php';
