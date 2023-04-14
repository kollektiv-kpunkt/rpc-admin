<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        $this->authenticate($request, $guards);
        if (auth()->user()->admin_activation == false && !auth()->user()->hasRole("admin")) {
            return response()->json([
                "message" => "Your account is not activated yet. Please contact the administrator."
            ], 403);
        }
        return $next($request);
    }
}
