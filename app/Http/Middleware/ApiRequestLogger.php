<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Jobs\SaveApiLogJob;
use Illuminate\Support\Facades\RateLimiter;

class ApiRequestLogger
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);

            $ip = $request->ip();
            $key = 'api-rate:' . $ip;

        if (RateLimiter::tooManyAttempts($key, 60)) {
            return response()->json([
                'error' => 'Too many requests'
            ], 429);
        }

        RateLimiter::hit($key, 60); // 60 seconds decay

        $response = $next($request);

        // sanitize request
        // $requestData = $request->except([
        //     'password',
        //     'token',
        //     'access_token',
        //     'refresh_token'
        // ]);
        
        $logData = [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'request_body' => json_encode($request->all()),
            'response_body' => $response->getContent(),
            'status_code' => $response->status(),
            'user_id' => optional(auth()->user())->id,
        ];

        // QUEUE DISPATCH
        SaveApiLogJob::dispatch($logData);

        return $response;
    }
}