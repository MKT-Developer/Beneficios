<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user->canAccessAdmin()) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Acceso restringido');
        }

        return $next($request);
    }
}
