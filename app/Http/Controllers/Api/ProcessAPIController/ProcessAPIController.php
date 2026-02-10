<?php

namespace App\Http\Controllers\API\ProcessAPIController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class ProcessAPIController extends Controller
{
    public function process(Request $request, $controller, $action)
    {

        try {


            // dispatch to middleware
            // $result = ValidatedReqExecuteReq::validatedExecute( $controller,  $action, $request->all());

            //API to microservice then returrn response and dispatch update and insert 

            $result = [
                "data" => '',
                'msg' => 'Success',
                'code' => '200'
            ];

            return ApiResponse::success($result["data"], $result["msg"], $result["code"]);
        } catch (\Exception $e) {
           
            return ApiResponse::error($e->getMessage(), null, 500);
        }
    }

}
