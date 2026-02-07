<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Helpers\ValidatedReqExecuteReq;
use App\Http\Requests\RegisterUserRequest;
use App\Actions\RegisterUser;
use App\Actions\LoginUser;
use App\DTOs\RegisterUserDTO;

use Exception;

class AuthController extends Controller
{

    public function __construct(
        protected RegisterUser $registerUser,
        protected LoginUser $LoginUser,
    ){}


  
    public function register(RegisterUserRequest $request)
    {
        try {

            $result = ValidatedReqExecuteReq::validatedExecute( RegisterUserDTO::class,  $this->registerUser,   $request);

            return ApiResponse::success($result, 'User registered successfully', 201);
        } catch (\Exception $e) {
           
            return ApiResponse::error($e->getMessage(), null, 500);
        }
    }

    public function login(Request $request) 
    {
        try {
            $credentials = $request->only('email', 'password');
      
            $result = $this->LoginUser->execute($credentials);

            return ApiResponse::success($result, 'Successfully Logged In', 200);
        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), null, 401);
        }
    }

    public function refresh()
    {
       try {
            $newToken = auth('api')->refresh();
        } catch(\Exception $e) {
            return ApiResponse::error('Token refresh failed', null, 401);
        }

        return ApiResponse::success($this->respondWithToken($newToken), 'New Token', 200);
    }

    public function me()
    {

        try {
            return ApiResponse::success(auth()->user(), 'User Information', 200);
        } catch(JWTException $e) {
            return ApiResponse::error('Token refresh failed', null, 401);
        }
        
    }

     // LOGOUT
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
     
    }

}
