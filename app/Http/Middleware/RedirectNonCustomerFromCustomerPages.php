<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectNonCustomerFromCustomerPages
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        return match (Auth::user()->role) {
            'admin' => redirect()->route('barang.index'),
            'kasir' => redirect()->route('kasir.index'),
            default => $next($request),
        };
    }
}
