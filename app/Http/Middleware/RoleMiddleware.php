<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if ($role === 'kasir' && !in_array($request->path(), ['kasir', 'penjualan/create', 'penjualan'])) {
            return redirect('/kasir')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        if (Auth::user()->role !== $role) {
            return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }        

        return $next($request);
    }
}
