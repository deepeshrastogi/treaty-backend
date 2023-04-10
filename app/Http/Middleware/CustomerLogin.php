<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;


class CustomerLogin {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {

        echo "here";die;

        if (auth()->user()->status == 'active') {
            return $next($request);
        }
        return response()->json('Your account is inactive');
    }
}
