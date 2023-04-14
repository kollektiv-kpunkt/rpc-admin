<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SiteKey
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
        $site = \App\Models\Site::findInAnyOrFail($request->site);
        if ($request->header("X-Site-Key") !== $site->key) {
            return response()->json([
                "code" => 403,
                "status" => "forbidden"
            ]);
        }
        return $next($request);
    }
}
