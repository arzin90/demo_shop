<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->type !== User::ADMINISTRATOR) {
            $code = Response::HTTP_FORBIDDEN;

            return response()->json([
                'message' => Response::$statusTexts[$code],
            ], $code);
        }

        return $next($request);
    }
}
