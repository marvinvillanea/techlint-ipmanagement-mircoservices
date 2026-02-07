<?php

namespace App\Http\Middleware;

use App\Jobs\SaveApiLogJob;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
class DBTransactionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        DB::beginTransaction();

        try {
            $response = $next($request);

            /*
            |--------------------------------------------------------------------------
            | If response error (HTTP >= 400) rollback
            |--------------------------------------------------------------------------
            */
            if ($response->getStatusCode() >= 400) {
                DB::rollBack();
                return $response;
            }

            DB::commit();

            return $response;

        } catch (Throwable $e) {
            DB::rollBack();
            $logData = [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'method' => 'FAILED DATABASE',
                'url' => $request->fullUrl(),
                'request_body' => json_encode($request->all()),
                'response_body' => $response->getContent(),
                'status_code' => $response->status(),
                'user_id' => optional(auth()->user())->id,
            ];

            // QUEUE DISPATCH
            SaveApiLogJob::dispatch($logData);
            return response()->json([
                'error' => 'Transaction failed',
                'message' => app()->environment('local')
                    ? $e->getMessage()
                    : 'Server error'
            ], 500);
        }
    }
}
