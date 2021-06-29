<?php

namespace App\Http\Middleware;

use Closure,Auth;

class db_main
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
        if (Auth::user()->privilege != 5) {
            return redirect('/');
        }

        return $next($request);
    }

}