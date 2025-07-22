<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Check404
{
    public function handle(Request $request, Closure $next)
    {
        // اگر می‌خوای همیشه صفحه 404 نشون بدی:
        abort(404);

        // یا اگر شرطی داری، بذار اینجا

        return $next($request);
    }
}
