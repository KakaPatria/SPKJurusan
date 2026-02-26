<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectBasedOnRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Redirect ke dashboard berdasarkan role setelah login
        if (auth()->check()) {
            $user = auth()->user();
            
            // Jika sudah di /dashboard, redirect ke panel yang sesuai
            if ($request->path() === 'dashboard') {
                if ($user->role === 'admin') {
                    return redirect('/admin/dashboard');
                } elseif ($user->role === 'bk') {
                    return redirect('/bk/dashboard');
                }
                // siswa tetap di /dashboard
            }
        }

        return $next($request);
    }
}
