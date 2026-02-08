<?php

namespace App\Http\Controllers\Api;

use App\Actions\GetClientToken;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Helpers\ApiResponse;
class ClientController extends Controller
{

     public function __construct(
        protected GetClientToken $GetClientToken,
    ){}

    public function register(Request $request)
    {
        
        try {

            $request->validate([
                'name' => 'required|string',
            ]);


            $data = $this->GetClientToken->execute($request->name);
            
            if($data){
                return ApiResponse::success($data, 'Success', 200);
            } else {
                return ApiResponse::error('Invalid Name!', null, 401);
            }

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), null, 401);
        }
       
    }
}
