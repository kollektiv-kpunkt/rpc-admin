<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SiteStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->route()->parameter("site")->status != "live") {
            return response()->json([
                "code" => 503,
                "status" => "Service Unavailable"
            ]);
        }
        return $next($request);
    }
}
