<?php

namespace App\Http\Middleware;

use App\Constants\Status;
use Closure;

class AllowRegistration
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
        if (gs('registration') == Status::DISABLE) {
            $notify[] = ['error', 'Registration is currently disabled'];
            return back()->withNotify($notify);
        }
        return $next($request);
    }
}
