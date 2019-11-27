<?php

namespace App\Http\Middleware;

use Closure;

class IsMobile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (isMobile()){
            return redirect("mobile");
        }
        return $next($request);
    }
}
