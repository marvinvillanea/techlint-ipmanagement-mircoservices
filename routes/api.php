<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ViewListTableData\ViewListTableData;

use Illuminate\Support\Facades\Route;



Route::prefix('v1')->group(function(){
    
    //CLIENT VERIFICATION CLIENT TOKEN PROVIDED BY OPERATOR
    Route::post('/client/register',[ClientController::class,'register']);


    //RENEWAL REFRESH TOKEN FOR APPLICATION
    Route::post('/auth/refresh',[AuthController::class,'refresh']);

    Route::middleware(['check.client.token'])->group(function(){

        Route::post('/auth/register',[AuthController::class,'register']);
        Route::post('/auth/login',[AuthController::class,'login']);

        Route::middleware(['auth:api'])->group(function(){
            Route::post('/auth/me',[AuthController::class,'me']);
            Route::post('/auth/logout',[AuthController::class,'logout']);
            Route::post('/view/{view_table}', [ViewListTableData::class, 'viewListTable']);
        });

    });

});


//MODULE CONTROLLER 

// Route::middleware([ClientTokenMiddleware::class])->group(function(){
//     Route::post('/register',[AuthController::class,'register']);
//     Route::post('/login',[AuthController::class,'login']);
// });