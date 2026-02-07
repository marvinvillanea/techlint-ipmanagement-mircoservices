<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function register(Request $request)
    {
        
        try {
            $request->validate([
                'name' => 'required|string',
            ]);

            $client = Client::create([
                'name' => $request->name,
                'client_token' => Str::random(60),
            ]);

            return response()->json($client, 201);
        } catch (\Exception $e) {
             return response()->json([
                'status'=> false,
                'message'=> $e->getMessage()
            ],201);
        }
       
    }
}
