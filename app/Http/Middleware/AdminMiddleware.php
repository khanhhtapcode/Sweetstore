<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để truy cập trang này.');
        }

        // Check if user is admin
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Bạn không có quyền truy cập vào khu vực quản trị.');
        }

        // Check if user is active
        if (!auth()->user()->isActive()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị vô hiệu hóa.');
        }

        return $next($request);
    }
}
