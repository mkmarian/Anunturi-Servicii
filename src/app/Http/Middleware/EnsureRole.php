<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Verifica ca utilizatorul autentificat are unul din rolurile permise.
     * Folosire in routes: ->middleware('role:craftsman')
     *                 sau ->middleware('role:admin,moderator')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(403, 'Nu ai permisiunea de a accesa aceasta pagina.');
        }

        return $next($request);
    }
}
