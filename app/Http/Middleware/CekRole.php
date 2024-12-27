<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$role): Response
    {
        // jika user kosong maka lempar ke halaman login
        $user = Auth::user();
        if ($user == null) {
            return redirect()->route('login');
        }

        foreach ($role as $roles) {
            if ($user->ref_group_id === $roles) {
                return $next($request);
            }
        }
        return redirect()->to(route('unauthorized'));
    }
}
