<?php

namespace App\Helpers;

class ValidatedReqExecuteReq
{
    public static function validatedExecute(string $dtoClass, $action, $request)
    {
        $dto = $dtoClass::fromRequest($request->validated());
        return $action->execute($dto);
    }
}
