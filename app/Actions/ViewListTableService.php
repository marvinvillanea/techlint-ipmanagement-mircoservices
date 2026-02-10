<?php 

namespace App\Actions;

use App\Repositories\ViewListTableRepository;

class ViewListTableService
{
    public function __construct(
        private ViewListTableRepository $repository
    ) {}

    public function execute($viewTable, $page, $search, $columns)
    {
        $selectedColumns = array_map(
            fn($col) => $col['accessorKey'] ?? null,
            $columns
        );

        $selectedColumns = array_filter($selectedColumns);

        // Validate columns via repository
        $validColumns = $this->repository->filterValidColumns(
            $viewTable,
            $selectedColumns
        );

        if (empty($validColumns)) {
            return null;
        }

        return $this->repository->getPaginatedData(
            $viewTable,
            $validColumns,
            $search,
            $page
        );
    }
}
