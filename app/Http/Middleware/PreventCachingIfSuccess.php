<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventCachingIfSuccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Biarkan request diproses oleh Laravel terlebih dahulu untuk menghasilkan response
        $response = $next($request);

        // Setelah response dibuat, kita cek apakah ada session 'success' (notifikasi kita)
        if ($request->session()->has('success')) {
            // Jika ADA, tambahkan header "anti-cache" ke response
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        // Kembalikan response yang sudah dimodifikasi (atau yang asli jika tidak ada notifikasi)
        return $response;
    }
}
