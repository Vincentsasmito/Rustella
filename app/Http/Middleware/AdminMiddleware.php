<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user(); // equivalent to auth()->user()

        // Allow only staff accounts
        if ($user && $user->role === 'Staff') {
            return $next($request);
        }

        return redirect()->route('home')
            ->with('error', 'You do not have access to that page.');
    }
}
