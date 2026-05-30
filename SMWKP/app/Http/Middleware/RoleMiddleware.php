<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect to respective home dashboard if they try to access unauthorized roles
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        } elseif ($user->role === 'owner') {
            return redirect()->route('owner.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        } else {
            return redirect()->route('tourist.jelajah')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }
    }
}
