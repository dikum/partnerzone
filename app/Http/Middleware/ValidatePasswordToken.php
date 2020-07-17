<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Authentication\TokenController;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Log;

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
        $response =  $next($request);

        Log::debug('Middleware called');

        return $response;
    }
}
