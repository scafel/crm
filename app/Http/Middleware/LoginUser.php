<?php

namespace App\Http\Middleware;

use Closure;

class LoginUser
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
        if (session('user_id_login')){
            return $next($request);
        }
        if (isMobile()){
            return redirect('mobile/login');
        }else{
            return redirect('home/login');
        }
    }
}
