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

        $param = $request->route('group') ?? $request->route('group_id');

        $group = $param instanceof Group ? $param : Group::find($param);

        abort_unless($group, 404);

        abort_unless($group->owner_id === Auth::id(), 403);

        return $next($request);


    }
}
