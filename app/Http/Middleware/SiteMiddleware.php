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
        $site = Site::findInAny($request->route()->parameter("site"));
        if (auth()->user()->role == "admin") {
            return $next($request);
        }
        if (!auth()->user()->hasAccessToSite($site->id)) {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
