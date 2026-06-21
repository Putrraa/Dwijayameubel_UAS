<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class kasir
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if(Auth::user()->role == 'kasir'){
            return $next($request);
        }

        return match (Auth::user()->role) {
            'admin' => redirect()->route('barang.index'),
            'customer' => redirect()->route('customer.index'),
            default => redirect('/login'),
        };
    }
}
