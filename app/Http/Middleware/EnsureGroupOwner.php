<?php

namespace App\Http\Middleware;

use App\Models\Group;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureGroupOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(Auth::check(), 401);
        
        $param = $request->route('group') ?? $request->route('group_id');

        if (is_null($param)) {
            abort_unless(Auth::user()->role === 2, 403);
            return $next($request);
        }

        $group = $param instanceof Group ? $param : Group::find($param);

        abort_unless($group, 404);
        abort_unless($group->owner_id === Auth::id(), 403);

        return $next($request);


    }
}
