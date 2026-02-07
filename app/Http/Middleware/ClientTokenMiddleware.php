<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Client-Token');

        if(!$token){
            return response()->json(['error'=>'Client token missing'],401);
        }

        $client = Client::where('client_token',$token)->first();

        if(!$client){
            return response()->json(['error'=>'Invalid client token'],403);
        }

        // $request->attributes->add(['client'=>$client]);

        return $next($request);
    }
}
