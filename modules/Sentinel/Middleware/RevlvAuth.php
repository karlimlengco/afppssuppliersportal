<?php

namespace Revlv\Sentinel\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RevlvAuth
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
        $currentUrl = \URL::current();
        if (!\Sentinel::check() ) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect('auth/login?redirect=' . $currentUrl);
            }
        }

        return $next($request);
    }
}
