<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMotherOnboarding
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->role === 'mother') {
            $mother = auth()->user()->mother;
            
            if ($mother && !$mother->is_onboarded && !$request->routeIs('mother.onboarding*')) {
                return redirect()->route('mother.onboarding');
            }
        }

        return $next($request);
    }
}
