<?php

namespace App\Services;

use App\Helpers\DataTableHelper;
use Illuminate\Http\Request;

class DataTableService
{
    /**
     * Common status badge renderer
     */
    public static function statusRenderer($item): string
    {
        $status = $item->status ?? 'inactive';
        $isActive = strtolower($status) == 'active';
        
        $badgeClass = $isActive ? 'bg-success' : 'bg-danger';
        $textClass = $isActive ? 'text-success' : 'text-danger';
        $text = $isActive ? 'active' : 'inactive';

        return "<span class=\"badge {$badgeClass} bg-opacity-10 {$textClass}\">{$text}</span>";
    }

    /**
     * Common actions renderer
     */
    public static function actionsRenderer(string $baseRoute): callable
    {
        return function ($item) use ($baseRoute) {
            return "
                <div class=\"d-inline-flex\">
                    <div class=\"dropdown\">
                        <a href=\"#\" class=\"text-body\" data-bs-toggle=\"dropdown\">
                            <i class=\"ph-list\"></i>
                        </a>
                        <div class=\"dropdown-menu dropdown-menu-end\">
                            <a href=\"/{$baseRoute}/{$item->id}\" class=\"dropdown-item\">
                                <i class=\"ph-eye me-2\"></i>View
                            </a>
                            <a href=\"/{$baseRoute}/{$item->id}/edit\" class=\"dropdown-item\">
                                <i class=\"ph-pencil me-2\"></i>Edit
                            </a>
                            <a href=\"#\" class=\"dropdown-item text-danger\" data-id=\"{$item->id}\" onclick=\"deleteItem({$item->id})\">
                                <i class=\"ph-trash me-2\"></i>Delete
                            </a>
                        </div>
                    </div>
                </div>
            ";
        };
    }

    /**
     * Create a configured DataTable helper for ComplaintCategory
     */
    public static function createComplaintCategoryTable(Request $request): DataTableHelper
    {
        $helper = new DataTableHelper($request, \App\Models\ComplaintCategory::class);
        
        return $helper
            ->addJoin('complaint_categories as parents', 'complaint_categories.parent_id', '=', 'parents.id')
            ->mapColumn('parent_category', 'parents.name')
            ->setSearchableColumns(['name', 'slug', 'parent_category'])
            ->setSortableColumns(['id', 'name', 'category_type', 'parent_category', 'status', 'created_at'])
            ->setFilterableColumns(['id', 'name', 'slug', 'status', 'category_type', 'parent_id', 'parent_category'])
            ->addRenderer('parent_category', function ($item) {
                return $item->parent ? $item->parent->name : '-';
            });
    }

    /**
     * Generic table creator with configuration
     */
    public static function createTable(Request $request, string $model, array $config): DataTableHelper
    {
        $helper = new DataTableHelper($request, $model);

        // Apply joins
        if (isset($config['joins'])) {
            foreach ($config['joins'] as $join) {
                $helper->addJoin(
                    $join['table'],
                    $join['first'],
                    $join['operator'],
                    $join['second'],
                    $join['type'] ?? 'left'
                );
            }
        }

        // Apply column mappings
        if (isset($config['mappings'])) {
            foreach ($config['mappings'] as $virtual => $actual) {
                $helper->mapColumn($virtual, $actual);
            }
        }

        // Set searchable columns
        if (isset($config['searchable'])) {
            $helper->setSearchableColumns($config['searchable']);
        }

        // Set sortable columns
        if (isset($config['sortable'])) {
            $helper->setSortableColumns($config['sortable']);
        }

        // Set filterable columns
        if (isset($config['filterable'])) {
            $helper->setFilterableColumns($config['filterable']);
        }

        // Apply renderers
        if (isset($config['renderers'])) {
            foreach ($config['renderers'] as $column => $renderer) {
                if (is_string($renderer) && method_exists(self::class, $renderer)) {
                    $helper->addRenderer($column, [self::class, $renderer]);
                } else {
                    $helper->addRenderer($column, $renderer);
                }
            }
        }

        return $helper;
    }
}