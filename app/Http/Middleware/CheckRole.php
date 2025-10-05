<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Chỉ cho phép user có vai_tro = 'quan_tri' vào
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Nếu chưa đăng nhập => đẩy về login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Nếu không phải admin => logout + quay về login
        if ($user->vai_tro !== 'quan_tri') {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Bạn không có quyền truy cập.');
        }

        return $next($request);
    }
}
