<?php
namespace App\Repositories;

use App\Models\Modules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
class ModulesRepository
{
    public function getModuleDetails(string $name)
    {
        return Modules::where('name', $name)->get();
    }
}
