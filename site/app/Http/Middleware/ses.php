<?php

namespace App\Http\Middleware;

use Closure,Auth;

class ses
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
        if (Auth::user()->privilege != 1) {
            return redirect('/');
        }

        return $next($request);
    }

}