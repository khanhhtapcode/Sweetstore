<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Nếu user đã đăng nhập và là admin, redirect về admin dashboard
        if (auth()->check() && auth()->user()->isAdmin() && $request->route()->getName() === 'dashboard') {
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
