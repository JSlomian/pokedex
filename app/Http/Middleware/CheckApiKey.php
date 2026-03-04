<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $provided = $request->header('X-SUPER-SECRET-KEY');
        $expected = config('app.api_key');

        if (! $provided) {
            return response()->json(
                ['message' => 'Missing key'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        if (! $expected || ! hash_equals($expected, $provided)) {
            return response()->json(
                ['message' => 'Invalid api key'],
                Response::HTTP_FORBIDDEN
            );
        }

        return $next($request);
    }
}
