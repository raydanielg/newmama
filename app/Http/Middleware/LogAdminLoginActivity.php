<?php

namespace App\Http\Middleware;

use App\Models\LoginActivity;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogAdminLoginActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $user = $request->user();
        if (!$user) {
            return $response;
        }

        if (!$request->session()->has('admin_login_logged')) {
            LoginActivity::create([
                'user_id' => $user->id,
                'guard' => 'web',
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 512),
                'path' => substr((string) $request->path(), 0, 255),
                'logged_at' => now(),
            ]);

            $request->session()->put('admin_login_logged', true);
        }

        return $response;
    }
}
