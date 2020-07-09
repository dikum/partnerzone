<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Authentication\TokenController;
use Carbon\Carbon;
use Closure;

class ValidatePasswordToken
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
        
        return $next($request);
    }
}
