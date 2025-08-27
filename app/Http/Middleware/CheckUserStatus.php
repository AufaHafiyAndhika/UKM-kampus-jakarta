<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user status is not active
            if ($user->status !== 'active') {
                Auth::logout();
                
                $message = match($user->status) {
                    'pending' => 'Akun Anda masih menunggu persetujuan admin. Silakan hubungi admin di WhatsApp: 081382640946',
                    'suspended' => 'Akun Anda telah disuspend. Silakan hubungi admin untuk informasi lebih lanjut.',
                    'inactive' => 'Akun Anda tidak aktif. Silakan hubungi admin untuk mengaktifkan akun.',
                    default => 'Status akun Anda tidak valid untuk mengakses sistem. Silakan hubungi admin.'
                };
                
                return redirect()->route('login')->with('error', $message);
            }
        }
        
        return $next($request);
    }
}
