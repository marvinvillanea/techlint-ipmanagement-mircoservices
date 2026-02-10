<?php

namespace App\Actions;

use App\Repositories\ModulesRepository;
use App\Repositories\IdempotencyRepository;
use App\Jobs\SendToMicroserviceJob;

class ProcessAPI
{
    public function __construct(
        private ModulesRepository $module,
        private IdempotencyRepository $Idompotency
    ) {}

    public function execute($request, $controller, $action): array
    {
        $checkModule = $this->module->getModuleDetails($controller);

        if ($checkModule->isEmpty()) {
            return [
                "data" => [],
                "msg" => "Failed No Module Found",
                "code" => 500
            ];
        }

        $module = $checkModule->first();

        // 👉 Idempotency check
        if (
            $module->unique_field != "" &&
            $request->has($module->unique_field) &&
            $action === "add"
        ) {
            $id = $module->name . $request->input($module->unique_field);

            if ($this->Idompotency->exists($id)) {
                return [
                    "data" => [],
                    "msg" => "Duplicate Data Unable to proceed!",
                    "code" => 201
                ];
            }
        }



        $this->SenttoJob($request, $controller, $action);
        


        return [
            "data" =>  $request->all() ,
            "msg" => "Success",
            "code" => 200
        ];
    }


    public function SenttoJob($request, $controller, $action):void
    {
        $jwtToken = $request->bearerToken();
        $requestData = $request->all(); // just input
        $userAgent = $request->userAgent();
        $ip = $request->ip();
        $fullUrl = $request->fullUrl();
        $clientTOken = $request->header('X-Client-Token');

        SendToMicroserviceJob::dispatch(
            url: $_ENV["APP_URL_MICROSERVICES"].'/api/v1/process/'.$controller.'/'.$action,
            payload: [
                'user' => auth()->user()?->only(['id', 'name', 'email']),
                'data' => $requestData,
            ],
            headers: [
                'X-Request-ID' => uniqid(),
                'X-Service' => 'ERP',
                'X-Client-Token' => $clientTOken,
            ],
            jwtToken: $jwtToken,
            meta: [
                'ip' => $ip,
                'user_agent' => $userAgent,
                'url' => $fullUrl,
                'method' => 'PROCESS MICROSERVICES '. $action
            ]
        );
    }

}
