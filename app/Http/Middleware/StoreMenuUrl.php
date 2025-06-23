<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreMenuUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/StoreMenuUrl.php

    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah rute saat ini adalah 'home'
        if ($request->routeIs('home')) {
            // Jika ya, simpan URL lengkapnya (termasuk filter) ke dalam session
            session(['menu_url' => $request->fullUrl()]);
        }

        return $next($request);
    }
}
