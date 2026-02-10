<?php

namespace App\Actions;

use App\Jobs\SaveApiLogJob;
use App\Repositories\ModulesRepository;
use App\Repositories\IdempotencyRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        // if (
        //     $module->unique_field != "" &&
        //     $request->has($module->unique_field) &&
        //     $action === "add"
        // ) {
        //     $id = $module->name . $request->input($module->unique_field);

        //     if ($this->Idompotency->exists($id)) {
        //         return [
        //             "data" => [],
        //             "msg" => "Duplicate Data Unable to proceed!",
        //             "code" => 201
        //         ];
        //     }
        // }



        $this->Action($request, $module, $controller, $action);

        return [
            "data" =>  $request->all() ,
            "msg" => "Success",
            "code" => 200
        ];
    }


    public function Action($request, $module, $controller, $action): void
    {
        $table = $module->table; // dynamic table
        $primaryKey = $module->primary_id; // dynamic primary key field
        $payload = $request->all(); // user input
        $latestData = '';
        // idempotency / unique field already handled in execute()

        // 🔑 Handle special fields like password
        if (isset($payload['password'])) {
            $payload['password'] = Hash::make($payload["password"]);
        }
        
        switch (strtolower($action)) {
            case 'add':
                // Insert new record
                \DB::table($table)->insert($payload);
                break;

            case 'edit':
                // Update existing record if primary key exists
                if (isset($payload[$primaryKey])) {
                    \DB::table($table)
                        ->where($primaryKey, $payload[$primaryKey])
                        ->update($payload);

                        // fetch the latest state
                    $latestData = \DB::table($table)
                        ->where($primaryKey, $payload[$primaryKey])
                        ->first();
                }
                break;

            case 'delete':
                // Delete record if primary key exists
                if (isset($payload[$primaryKey])) {
                    // Instead of deleting, just mark as deleted
                    \DB::table($table)
                        ->where($primaryKey, $payload[$primaryKey])
                        ->update(['deleted' => 1]);

                    $latestData = \DB::table($table)
                    ->where($primaryKey, $payload[$primaryKey])
                    ->first();
                }
                break;

            default:
                throw new \Exception("Unknown action: $action");
        }


        $logData = [
            'ip' =>  $request->ip(),
            'user_agent' => $request->userAgent(),
            'method' =>  $request->method(),
            'url' =>  $request->fullUrl(),
            'request_body' =>   json_encode($payload),
            'response_body' =>  json_encode($latestData),
            'status_code' => 200,
            'user_id' => optional(auth()->user())->id,
        ];

        SaveApiLogJob::dispatch($logData);

    }


}
