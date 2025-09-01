<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Exception;

class DataTableHelper
{
    protected Request $request;
    protected Builder $query;
    protected string $model;
    protected array $joins = [];
    protected array $columnMappings = [];
    protected array $searchableColumns = [];
    protected array $sortableColumns = [];
    protected array $filterableColumns = [];
    protected array $customRenderers = [];
    protected bool $autoJoin = false;
    protected string $primaryTable;

    public function __construct(Request $request, string $model)
    {
        $this->request = $request;
        $this->model = $model;
        $this->query = $model::query();
        $this->primaryTable = (new $model)->getTable();
    }

    /**
     * Add a join to the query
     */
    public function addJoin(string $table, string $first, string $operator, string $second, string $type = 'left'): self
    {
        $this->joins[] = [
            'table' => $table,
            'first' => $first,
            'operator' => $operator,
            'second' => $second,
            'type' => $type
        ];
        return $this;
    }

    /**
     * Map a column name to its actual database column
     */
    public function mapColumn(string $virtualColumn, string $actualColumn): self
    {
        $this->columnMappings[$virtualColumn] = $actualColumn;
        return $this;
    }

    /**
     * Set searchable columns
     */
    public function setSearchableColumns(array $columns): self
    {
        $this->searchableColumns = $columns;
        return $this;
    }

    /**
     * Set sortable columns
     */
    public function setSortableColumns(array $columns): self
    {
        $this->sortableColumns = $columns;
        return $this;
    }

    /**
     * Set filterable columns
     */
    public function setFilterableColumns(array $columns): self
    {
        $this->filterableColumns = $columns;
        return $this;
    }

    /**
     * Add custom renderer for a column
     */
    public function addRenderer(string $column, callable $renderer): self
    {
        $this->customRenderers[$column] = $renderer;
        return $this;
    }

    /**
     * Enable auto join (joins all mapped columns automatically)
     */
    public function enableAutoJoin(): self
    {
        $this->autoJoin = true;
        return $this;
    }

    /**
     * Get the actual column name (mapped or original)
     */
    protected function getActualColumn(string $column): string
    {
        return $this->columnMappings[$column] ?? $this->primaryTable . '.' . $column;
    }

    /**
     * Check if joins are needed based on request - IMPROVED VERSION
     */
    protected function needsJoins(): bool
    {
        // If auto join is enabled, always join
        if ($this->autoJoin) {
            return true;
        }

        // If we have no joins or mappings, no need to join
        if (empty($this->joins) || empty($this->columnMappings)) {
            return false;
        }

        $columns = $this->request->input('columns', []);
        $orders = $this->request->input('order', []);

        // Check sorting - FIXED LOGIC
        foreach ($orders as $order) {
            $colIdx = $order['column'] ?? null;
            if ($colIdx !== null && isset($columns[$colIdx])) {
                $colName = $columns[$colIdx]['data'] ?? null;
                Log::info('Checking sort column', ['colIdx' => $colIdx, 'colName' => $colName, 'mapped' => isset($this->columnMappings[$colName])]);
                if ($colName && isset($this->columnMappings[$colName])) {
                    Log::info('Join needed for sorting', ['column' => $colName]);
                    return true;
                }
            }
        }

        // Check API-style sorting
        if ($this->request->filled('sort_by')) {
            $sortBy = $this->request->get('sort_by');
            if (isset($this->columnMappings[$sortBy])) {
                Log::info('Join needed for API sorting', ['sort_by' => $sortBy]);
                return true;
            }
        }

        // Check column filtering
        foreach ($columns as $col) {
            $colName = $col['data'] ?? null;
            $colSearch = $col['search']['value'] ?? null;
            if ($colName && $colSearch && isset($this->columnMappings[$colName])) {
                Log::info('Join needed for column filter', ['column' => $colName]);
                return true;
            }
        }

        // Check direct filtering
        foreach (array_keys($this->columnMappings) as $mappedColumn) {
            if ($this->request->filled($mappedColumn)) {
                Log::info('Join needed for direct filter', ['column' => $mappedColumn]);
                return true;
            }
        }

        // Check global search for mapped columns
        $globalSearch = $this->request->input('search.value', $this->request->input('search'));
        if (!empty($globalSearch)) {
            foreach ($this->searchableColumns as $searchColumn) {
                if (isset($this->columnMappings[$searchColumn])) {
                    Log::info('Join needed for global search', ['column' => $searchColumn]);
                    return true;
                }
            }
        }

        Log::info('No join needed');
        return false;
    }

    /**
     * Apply joins to the query
     */
    protected function applyJoins(): void
    {
        if ($this->needsJoins()) {
            Log::info('Applying joins', ['joins' => $this->joins]);
            foreach ($this->joins as $join) {
                $method = $join['type'] . 'Join';
                $this->query->$method($join['table'], $join['first'], $join['operator'], $join['second']);
            }
            $this->query->select($this->primaryTable . '.*');
            Log::info('Joins applied', ['sql' => $this->query->toSql()]);
        } else {
            Log::info('No joins applied');
        }
    }

    /**
     * Apply global search
     */
    protected function applyGlobalSearch(): void
    {
        $globalSearch = $this->request->input('search.value', $this->request->input('search'));
        if (empty($globalSearch) || empty($this->searchableColumns)) {
            return;
        }

        $this->query->where(function ($q) use ($globalSearch) {
            foreach ($this->searchableColumns as $column) {
                $actualColumn = $this->getActualColumn($column);
                $q->orWhere($actualColumn, 'like', '%' . $globalSearch . '%');
            }
        });
    }

    /**
     * Apply column-specific filters
     */
    protected function applyColumnFilters(): void
    {
        $columns = $this->request->input('columns', []);

        foreach ($columns as $col) {
            $colName = $col['data'] ?? null;
            $colSearch = $col['search']['value'] ?? null;

            if (!$colName || !$colSearch || !in_array($colName, $this->filterableColumns)) {
                continue;
            }

            $actualColumn = $this->getActualColumn($colName);

            if (in_array($colName, ['id', 'parent_id']) || str_ends_with($colName, '_id')) {
                $this->query->where($actualColumn, $colSearch);
            } else {
                $this->query->whereRaw('LOWER(' . $actualColumn . ') LIKE ?', ['%' . strtolower($colSearch) . '%']);
            }
        }
    }

    /**
     * Apply direct field filters
     */
    protected function applyDirectFilters(): void
    {
        foreach ($this->filterableColumns as $field) {
            if (!$this->request->filled($field)) {
                continue;
            }

            $value = $this->request->get($field);
            $actualColumn = $this->getActualColumn($field);

            if (in_array($field, ['id', 'parent_id']) || str_ends_with($field, '_id')) {
                $this->query->where($actualColumn, $value);
            } else {
                $this->query->whereRaw('LOWER(' . $actualColumn . ') LIKE ?', ['%' . strtolower($value) . '%']);
            }
        }
    }

    /**
     * Apply sorting - IMPROVED VERSION
     */
    protected function applySorting(): void
    {
        $columns = $this->request->input('columns', []);
        $orders = $this->request->input('order', []);
        $isDataTables = $this->request->has('draw');

        Log::info('Applying sorting', [
            'isDataTables' => $isDataTables,
            'orders' => $orders,
            'columns' => $columns,
            'sortableColumns' => $this->sortableColumns
        ]);

        if ($isDataTables && !empty($orders)) {
            foreach ($orders as $order) {
                $colIdx = $order['column'] ?? null;
                $colDir = $order['dir'] ?? 'asc';

                if ($colIdx !== null && isset($columns[$colIdx])) {
                    $colName = $columns[$colIdx]['data'] ?? null;

                    Log::info('Processing sort order', [
                        'colIdx' => $colIdx,
                        'colName' => $colName,
                        'colDir' => $colDir,
                        'isSortable' => in_array($colName, $this->sortableColumns)
                    ]);

                    if ($colName && in_array($colName, $this->sortableColumns)) {
                        $actualColumn = $this->getActualColumn($colName);
                        Log::info('Adding order by', ['actualColumn' => $actualColumn, 'direction' => $colDir]);
                        $this->query->orderBy($actualColumn, $colDir);
                    }
                }
            }
        } elseif ($this->request->filled('sort_by')) {
            $colName = $this->request->get('sort_by');
            $colDir = $this->request->get('sort_dir', 'asc');

            if (in_array($colName, $this->sortableColumns)) {
                $actualColumn = $this->getActualColumn($colName);
                $this->query->orderBy($actualColumn, $colDir);
            }
        } else {
            // Default sorting
            $this->query->orderBy($this->primaryTable . '.created_at', 'desc');
        }
    }

    /**
     * Apply custom renderers to data
     */
    protected function applyRenderers($data): void
    {
        if (empty($this->customRenderers)) {
            return;
        }

        $data->transform(function ($item) {
            foreach ($this->customRenderers as $column => $renderer) {
                $item->$column = $renderer($item);
            }
            return $item;
        });
    }

    /**
     * Get pagination parameters
     */
    protected function getPaginationParams(): array
    {
        $isDataTables = $this->request->has('draw');

        if ($isDataTables) {
            $start = intval($this->request->get('start', 0));
            $length = intval($this->request->get('length', 10));
            $page = intval($start / $length) + 1;
        } else {
            $length = intval($this->request->get('per_page', 10));
            $page = intval($this->request->get('page', 1));
            $start = ($page - 1) * $length;
        }

        return [$start, $length, $page, $isDataTables];
    }

    /**
     * Handle "all" data request
     */
    protected function handleAllRequest()
    {
        if ($this->request->get('all') !== 'true') {
            return null;
        }

        $this->applyJoins();
        $this->applyGlobalSearch();
        $this->applyColumnFilters();
        $this->applyDirectFilters();

        $data = $this->query->orderBy($this->primaryTable . '.name')->get();
        $this->applyRenderers($data);

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => 'All records retrieved successfully'
        ]);
    }

    /**
     * Generate the response
     */
    public function response()
    {
        try {
            // Handle "all" request
            $allResponse = $this->handleAllRequest();
            if ($allResponse) {
                return $allResponse;
            }

            // Get pagination parameters
            [$start, $length, $page, $isDataTables] = $this->getPaginationParams();

            // Apply query modifications in the correct order
            $this->applyJoins(); // Apply joins FIRST
            $this->applyGlobalSearch();
            $this->applyColumnFilters();
            $this->applyDirectFilters();
            $this->applySorting(); // Apply sorting AFTER joins

            // Log the final query for debugging
            Log::info('Final query', ['sql' => $this->query->toSql(), 'bindings' => $this->query->getBindings()]);

            // Get totals
            $recordsTotal = $this->model::count();
            $recordsFiltered = $this->query->count();

            // Get data
            $data = $this->query->skip($start)->take($length)->get();
            $this->applyRenderers($data);

            // Return appropriate response
            if ($isDataTables) {
                return response()->json([
                    'draw' => intval($this->request->get('draw', 1)),
                    'recordsTotal' => $recordsTotal,
                    'recordsFiltered' => $recordsFiltered,
                    'data' => $data,
                ]);
            } else {
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'data' => $data,
                        'total' => $recordsFiltered,
                        'per_page' => $length,
                        'page' => $page,
                    ],
                    'message' => 'Records retrieved successfully'
                ]);
            }
        } catch (Exception $e) {
            Log::error('DataTable Helper Error: ' . $e->getMessage(), [
                'request' => $this->request->all(),
                'model' => $this->model,
                'trace' => $e->getTraceAsString()
            ]);

            $isDataTables = $this->request->has('draw');

            if ($isDataTables) {
                return response()->json([
                    'draw' => intval($this->request->get('draw', 1)),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => [],
                    'error' => 'An error occurred while processing the request'
                ], 500);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred while processing the request'
                ], 500);
            }
        }
    }
}
