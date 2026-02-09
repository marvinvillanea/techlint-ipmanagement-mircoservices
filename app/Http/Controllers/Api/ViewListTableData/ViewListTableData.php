<?php

namespace App\Http\Controllers\Api\ViewListTableData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ViewListTableData extends Controller
{
    //
    public function viewListTable(Request $request, $view_table)
    {

        $page = $request->input('page', 1);
        $search = $request->input('search', '');
        $columns = $request->input('column', []);

        // Extract the keys (accessorKey) the frontend wants
        $selectedColumns = array_map(fn($col) => $col['accessorKey'], $columns);

        // Make sure selected columns exist in the table
        $tableColumns = Schema::getColumnListing($view_table);
        $selectedColumns = array_filter($selectedColumns, fn($col) => in_array($col, $tableColumns));

        if (empty($selectedColumns)) {
            return ApiResponse::error('No valid columns selected', null, 400);

        }

        $query = DB::table($view_table)->select($selectedColumns);

        // Apply search if provided
        if ($search) {
            $query->where(function($q) use ($selectedColumns, $search) {
                foreach ($selectedColumns as $col) {
                    $q->orWhere($col, 'like', "%{$search}%");
                }
                    $q->where("id", '!=', "1");

            });
        }

        $query->where('id', '!=', 1);

        $data = $query->paginate($page*10);

        return ApiResponse::success($data, 'Success', 200);

    }
}
