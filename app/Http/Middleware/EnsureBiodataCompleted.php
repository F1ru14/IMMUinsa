<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureBiodataCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && in_array($user->role, [Role::Fasilitator, Role::Kader], true)) {
            if (! $user->is_active) {
                if (! $request->routeIs('dashboard')) {
                    return redirect()->route('dashboard')
                        ->with('warning', 'Akun Anda masih menunggu persetujuan Administrator.');
                }
            } elseif (! $user->hasCompletedBiodata()) {
                if (! $request->routeIs('biodata.*') && ! $request->routeIs('logout')) {
                    return redirect()->route('biodata.create')
                        ->with('warning', 'Please complete your biodata profile before accessing the dashboard.');
                }
            }
        }

        return $next($request);
    }
}
