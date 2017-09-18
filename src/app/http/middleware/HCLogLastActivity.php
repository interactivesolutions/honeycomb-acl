<?php

namespace interactivesolutions\honeycombacl\app\http\middleware;

use Closure;

class HCLogLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if( auth()->check() ) {
            $user = auth()->user();
            $user->updateLastActivity();
        }

        return $next($request);
    }
}
