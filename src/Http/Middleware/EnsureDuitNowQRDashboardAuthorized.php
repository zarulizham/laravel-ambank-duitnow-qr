<?php

namespace ZarulIzham\DuitNowQR\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use ZarulIzham\DuitNowQR\DuitNowQR;

class EnsureDuitNowQRDashboardAuthorized
{
    public function handle(Request $request, Closure $next, string $mode = 'dashboard'): Response
    {
        $duitNowQR = app(DuitNowQR::class);
        $authCallbackConfigured = $duitNowQR->hasDashboardAuthCallback();
        $authorized = $duitNowQR->authorizeDashboard($request);

        if ($mode === 'api') {
            if (! $request->user()) {
                abort(401);
            }

            if ($authCallbackConfigured && ! $authorized) {
                abort(403);
            }
        } else {
            abort_unless($authorized, 403);
        }

        return $next($request);
    }
}
