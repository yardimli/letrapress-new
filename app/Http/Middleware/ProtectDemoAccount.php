<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProtectDemoAccount
{
    public function handle(Request $request, Closure $next): Response
    {
        $isWrite = ! in_array($request->method(), ['GET', 'HEAD', 'OPTIONS'], true);
        $isAllowedSessionAction = $request->routeIs(
            'logout',
            'demo.login',
            'ajax.directory.human-verify',
            'press-releases.analyze'
        );

        if ($request->user()?->is_demo && $isWrite && ! $isAllowedSessionAction) {
            $message = 'This is a read-only demo. Changes are disabled.';

            if ($request->expectsJson() || $request->is('ajax/*')) {
                return response()->json(['message' => $message], 403);
            }

            return back()->withErrors(['demo' => $message]);
        }

        return $next($request);
    }
}
