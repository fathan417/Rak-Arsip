<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!session('is_admin')) {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
