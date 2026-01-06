<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Jika belum login, tendang ke login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Jika login tapi role-nya tidak sesuai, tendang balik
        if (Auth::user()->role != $role) {
            return redirect('/home')->with('error', 'Anda tidak punya akses ke halaman tersebut!');
        }

        return $next($request);
    }
}