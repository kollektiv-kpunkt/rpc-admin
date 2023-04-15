<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Site;

class SiteMiddleware
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
        $site = request()->route()->parameter("site");
        if (auth()->user()->role == "admin") {
            return $next($request);
        }
        if (!auth()->user()->hasAccessToSite($site)) {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
