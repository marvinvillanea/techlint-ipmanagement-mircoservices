<?php

namespace App\Http\Controllers\API\ProcessAPIController;

use App\Actions\ProcessAPI;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Helpers\ControllerHelper;

class ProcessAPIController extends Controller
{

    public function __construct(
        protected ControllerHelper $ControllerHelper,
        protected ProcessAPI $ProcessAPI
        
    ){}

    public function process(Request $request, $controller, $action)
    {

        try {

            if($this->ControllerHelper->checkParamRoute($controller, $action))  {
                return ApiResponse::error('Missing Paramater Request Header', null, 201);
            }


            $result = $this->ProcessAPI->execute($request, $controller, $action);

            return ApiResponse::success($result["data"], $result["msg"], $result["code"]);

        } catch (\Exception $e) {
            
            return ApiResponse::error($e->getMessage(), null, 500);
        
        }
    }

}
