<?php

namespace interactivesolutions\honeycombacl\app\http\middleware;

use Closure;
use Illuminate\Http\Request;

class HCACLPermissionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param null $permission
     * @return mixed
     */
    public function handle (Request $request, Closure $next,  $permission = null)
    {
        if (count ($request->segments ()) == 1 && $request->segment (1) == config('hc.admin_url'))
            $access = true;
        else
            $access = $request->user ()->can ($permission);

        if (!$access)
            abort(401);

        return $next($request);
    }
}