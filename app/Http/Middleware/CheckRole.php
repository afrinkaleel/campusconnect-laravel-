<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (auth()->user()->user_type !== $role) {
            // Wrong role — redirect to their own dashboard
            $type = auth()->user()->user_type;
            return redirect('/dashboard/' . $type);
        }

        return $next($request);
    }
}