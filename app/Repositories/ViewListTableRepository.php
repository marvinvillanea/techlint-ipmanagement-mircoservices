<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ViewListTableRepository
{
    public function filterValidColumns($table, $columns)
    {
        $tableColumns = Schema::getColumnListing($table);

        return array_values(array_filter(
            $columns,
            fn($col) => in_array($col, $tableColumns)
        ));
    }

    public function getPaginatedData($table, $columns, $search, $page)
    {
        $query = DB::table($table)->select($columns);

        if ($search) {
            $query->where(function ($q) use ($columns, $search) {
                foreach ($columns as $col) {
                    $q->orWhere($col, 'like', "%{$search}%");
                }
            });
        }

        // Business rule example
        if ($table === "v_usermanagement") {
            $query->where('id', '!=', 1);
        }

        return $query->paginate($page * 10);
    }
}
