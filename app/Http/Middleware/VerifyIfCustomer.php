<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class VerifyIfCustomer {
    use ApiResponse;
    public $userData = false;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public $attributes;

    public function handle(Request $request, Closure $next) {
        $this->userData = $this->checkToken($request->bearerToken(), 200);
        if ($this->userData == false) {
            return $this->error(['error' => [trans('messages.unauthorize')]], 401);
        }
        return $next($request);
    }
}