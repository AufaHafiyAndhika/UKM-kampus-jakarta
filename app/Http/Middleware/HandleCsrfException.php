<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

class HandleCsrfException
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (TokenMismatchException $e) {
            // If it's a logout request, handle gracefully
            if ($request->is('logout') || $request->route()?->getName() === 'logout') {
                // Clear session and redirect to home
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect('/')->with('warning', 'Session telah berakhir. Anda telah logout secara otomatis.');
            }
            
            // For other requests, redirect to login with error message
            return redirect()->route('login')->with('error', 'Session telah berakhir. Silakan login kembali.');
        }
    }
}
