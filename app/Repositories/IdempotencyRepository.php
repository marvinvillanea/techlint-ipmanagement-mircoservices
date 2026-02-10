<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use App\Models\Idompotency;

class IdempotencyRepository
{
    /**
     * Insert record only if not exists
     *
     * @param string $uniqueField
     * @throws \Exception
     */
    public function exists(string $uniqueid) :bool
    {
        return Idompotency::firstOrCreate(
            ['uniqueid' => $uniqueid]
        )->wasRecentlyCreated === false;
    }
}
