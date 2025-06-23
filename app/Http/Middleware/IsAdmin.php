<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek session sederhana
        if (session('is_admin')) {
            return $next($request);
        }
        return redirect()->route('admin.login');
    }
}