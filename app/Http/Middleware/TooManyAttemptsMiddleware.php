<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class TooManyAttemptsMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param int|null $maxAttempts
     * @param int|null $decayMinutes
     * @param string $messageKey
     * @return Response
     */
    public function handle(Request $request, Closure $next, ?int $maxAttempts = null, ?int $decayMinutes = null, string $messageKey = 'messages.http.tooManyRequests'): Response
    {
        $maxAttempts = $maxAttempts ?? config('custom.max_attempts');
        $decayMinutes = $decayMinutes ?? config('custom.decay_minutes');

        if (RateLimiter::tooManyAttempts(request()->ip(), $maxAttempts)) {
            return response()->json(
                ['message' => __($messageKey, ['minutes' => $decayMinutes])],
                Response::HTTP_TOO_MANY_REQUESTS,
            );
        }

        return $next($request);
    }
}
