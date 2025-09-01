<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Constants\SystemEnums;
use App\Facades\ApiResponse;
use App\Facades\Complaint;
use App\Helpers\DataTableHelper;
use App\Http\Controllers\Controller;
use App\Models\ComplaintCategory;
use App\Services\DataTableService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ComplaintCategoryController extends Controller
{
    public function __construct() {}

    /**
     * Display a listing of the resource - Main DataTable
     */
    public function index(Request $request)
    {
        return DataTableService::createComplaintCategoryTable($request)->response();
    }

    /**
     * Show the form for creating a new resource
     */
    public function create()
    {
        $parentCategories = ComplaintCategory::whereNull('parent_id')
            ->where('status', SystemEnums::STATUS_ACTIVE)
            ->orderBy('name')
            ->get(['id', 'name']);

        $categoryTypes = [
            'main' => 'Main Category',
            'sub' => 'Sub Category'
        ];

        $data = [
            'parent_categories' => $parentCategories,
            'category_types' => $categoryTypes,
            'statuses' => [
                SystemEnums::STATUS_ACTIVE => 'Active',
                SystemEnums::STATUS_INACTIVE => 'Inactive'
            ]
        ];

        return ApiResponse::success('Create form data retrieved successfully', $data);
    }

    /**
     * Store a newly created resource in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255|unique:complaint_categories',
            'parent_id'     => 'nullable|exists:complaint_categories,id',
            'category_type' => 'required|string',
            'description'   => 'nullable|string|max:1000',
            'status'        => 'nullable|in:' . SystemEnums::STATUS_ACTIVE . ',' . SystemEnums::STATUS_INACTIVE,
        ]);

        // Auto-generate slug if not provided
        if (!isset($validated['slug']) || empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Ensure slug uniqueness
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (ComplaintCategory::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Set default status
        if (!isset($validated['status'])) {
            $validated['status'] = SystemEnums::STATUS_ACTIVE;
        }

        // Validate parent-child relationship
        if (isset($validated['parent_id']) && $validated['category_type'] === 'main') {
            return ApiResponse::error('Main categories cannot have a parent category', 422);
        }

        if (!isset($validated['parent_id']) && $validated['category_type'] === 'sub') {
            return ApiResponse::error('Sub categories must have a parent category', 422);
        }

        $category = Complaint::createComplaintCategory($validated);

        // Clear cache
        $this->clearCategoriesCache();

        return ApiResponse::success('Complaint category created successfully', $category, 201);
    }

    /**
     * Store method using the original method name
     */
    public function storeComplaintCategory(Request $request)
    {
        return $this->store($request);
    }

    /**
     * Display the specified resource
     */
    public function show($id)
    {
        $category = ComplaintCategory::with(['parent', 'children'])
            ->findOrFail($id);

        $statistics = [
            // 'total_complaints' => $category->complaints()->count(),
            // 'active_complaints' => $category->complaints()->where('status', 'active')->count(),
            'children_count' => $category->children()->count(),
            'created_at' => $category->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $category->updated_at->format('Y-m-d H:i:s'),
        ];

        $data = [
            'category' => $category,
            'statistics' => $statistics
        ];

        return ApiResponse::success('Complaint category retrieved successfully', $data);
    }

    /**
     * Show the form for editing the specified resource
     */
    public function edit($id)
    {
        $category = ComplaintCategory::findOrFail($id);
        
        $parentCategories = ComplaintCategory::whereNull('parent_id')
            ->where('status', SystemEnums::STATUS_ACTIVE)
            ->where('id', '!=', $id) // Exclude current category
            ->orderBy('name')
            ->get(['id', 'name']);

        $categoryTypes = [
            'main' => 'Main Category',
            'sub' => 'Sub Category'
        ];

        $data = [
            'category' => $category,
            'parent_categories' => $parentCategories,
            'category_types' => $categoryTypes,
            'statuses' => [
                SystemEnums::STATUS_ACTIVE => 'Active',
                SystemEnums::STATUS_INACTIVE => 'Inactive'
            ]
        ];

        return ApiResponse::success('Edit form data retrieved successfully', $data);
    }

    /**
     * Update the specified resource in storage
     */
    public function update(Request $request, $id)
    {
        $category = ComplaintCategory::findOrFail($id);

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255|unique:complaint_categories,slug,' . $id,
            'parent_id'     => 'nullable|exists:complaint_categories,id',
            'category_type' => 'required|string',
            'description'   => 'nullable|string|max:1000',
            'status'        => 'required|in:' . SystemEnums::STATUS_ACTIVE . ',' . SystemEnums::STATUS_INACTIVE,
        ]);

        // Auto-generate slug if not provided
        if (!isset($validated['slug']) || empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
            
            // Ensure slug uniqueness (excluding current record)
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (ComplaintCategory::where('slug', $validated['slug'])->where('id', '!=', $id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Validate parent-child relationship
        if (isset($validated['parent_id'])) {
            if ($validated['parent_id'] == $id) {
                return ApiResponse::error('Category cannot be its own parent', 422);
            }

            if ($validated['category_type'] === 'main') {
                return ApiResponse::error('Main categories cannot have a parent category', 422);
            }

            // Check for circular reference
            if ($this->wouldCreateCircularReference($id, $validated['parent_id'])) {
                return ApiResponse::error('This would create a circular reference', 422);
            }
        }

        if (!isset($validated['parent_id']) && $validated['category_type'] === 'sub') {
            return ApiResponse::error('Sub categories must have a parent category', 422);
        }

        $category->update($validated);

        // Clear cache
        $this->clearCategoriesCache();

        return ApiResponse::success('Complaint category updated successfully', $category->fresh());
    }

    /**
     * Remove the specified resource from storage
     */
    public function destroy($id)
    {
        $category = ComplaintCategory::findOrFail($id);

        // Check if category has children
        if ($category->children()->count() > 0) {
            return ApiResponse::error('Cannot delete category that has subcategories. Please delete or reassign subcategories first.', 422);
        }

        // Check if category has complaints
        if ($category->complaints()->count() > 0) {
            return ApiResponse::error('Cannot delete category that has associated complaints. Please reassign complaints first.', 422);
        }

        $categoryName = $category->name;
        $category->delete();

        // Clear cache
        $this->clearCategoriesCache();

        return ApiResponse::success("Complaint category '{$categoryName}' deleted successfully");
    }

    /**
     * Get data for DataTable
     */
    public function getData(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Alternative datatable method
     */
    public function datatable(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Toggle status of a category
     */
    public function toggleStatus($id)
    {
        $category = ComplaintCategory::findOrFail($id);
        $newStatus = $category->status === SystemEnums::STATUS_ACTIVE 
            ? SystemEnums::STATUS_INACTIVE 
            : SystemEnums::STATUS_ACTIVE;
        
        $category->update(['status' => $newStatus]);

        // Clear cache
        $this->clearCategoriesCache();

        $data = [
            'status' => $newStatus,
            'category' => $category
        ];

        return ApiResponse::success('Status updated successfully', $data);
    }

    /**
     * Activate a category
     */
    public function activate($id)
    {
        $category = ComplaintCategory::findOrFail($id);
        $category->update(['status' => SystemEnums::STATUS_ACTIVE]);

        // Clear cache
        $this->clearCategoriesCache();

        return ApiResponse::success('Category activated successfully', $category);
    }

    /**
     * Deactivate a category
     */
    public function deactivate($id)
    {
        $category = ComplaintCategory::findOrFail($id);
        $category->update(['status' => SystemEnums::STATUS_INACTIVE]);

        // Clear cache
        $this->clearCategoriesCache();

        return ApiResponse::success('Category deactivated successfully', $category);
    }

    /**
     * Search categories
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $limit = $request->get('limit', 10);
        $type = $request->get('type');
        $status = $request->get('status');

        $categories = ComplaintCategory::query()
            ->when($query, function ($q, $query) {
                return $q->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
            })
            ->when($type, function ($q, $type) {
                return $q->where('category_type', $type);
            })
            ->when($status, function ($q, $status) {
                return $q->where('status', $status);
            })
            ->limit($limit)
            ->orderBy('name')
            ->get();

        return ApiResponse::success('Search results retrieved successfully', $categories);
    }

    /**
     * Search parent categories for dropdowns
     */
    public function searchParents(Request $request)
    {
        $search = $request->get('q', '');
        $limit = $request->get('limit', 10);
        
        $parents = ComplaintCategory::whereNull('parent_id')
            ->where('status', SystemEnums::STATUS_ACTIVE)
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit($limit)
            ->get(['id', 'name']);

        return ApiResponse::success('Parent categories retrieved successfully', $parents);
    }

    /**
     * Filter by category type
     */
    public function filterByType($type)
    {
        $categories = ComplaintCategory::where('category_type', $type)
            ->orderBy('name')
            ->get();

        return ApiResponse::success("Categories of type '{$type}' retrieved successfully", $categories);
    }

    /**
     * Filter by status
     */
    public function filterByStatus($status)
    {
        $categories = ComplaintCategory::where('status', $status)
            ->orderBy('name')
            ->get();

        return ApiResponse::success("Categories with status '{$status}' retrieved successfully", $categories);
    }

    /**
     * Get all categories
     */
    public function getAll()
    {
        $categories = Cache::remember('complaint_categories_all', 3600, function () {
            return ComplaintCategory::with('parent')
                ->orderBy('name')
                ->get();
        });

        return ApiResponse::success('All categories retrieved successfully', $categories);
    }

    /**
     * Get active categories
     */
    public function getActive()
    {
        $categories = Cache::remember('complaint_categories_active', 3600, function () {
            return ComplaintCategory::where('status', SystemEnums::STATUS_ACTIVE)
                ->with('parent')
                ->orderBy('name')
                ->get();
        });

        return ApiResponse::success('Active categories retrieved successfully', $categories);
    }

    /**
     * Get main categories
     */
    public function getMainCategories()
    {
        $categories = Cache::remember('complaint_categories_main', 3600, function () {
            return ComplaintCategory::whereNull('parent_id')
                ->where('status', SystemEnums::STATUS_ACTIVE)
                ->with('children')
                ->orderBy('name')
                ->get();
        });

        return ApiResponse::success('Main categories retrieved successfully', $categories);
    }

    /**
     * Get sub categories
     */
    public function getSubCategories()
    {
        $categories = Cache::remember('complaint_categories_sub', 3600, function () {
            return ComplaintCategory::whereNotNull('parent_id')
                ->where('status', SystemEnums::STATUS_ACTIVE)
                ->with('parent')
                ->orderBy('name')
                ->get();
        });

        return ApiResponse::success('Sub categories retrieved successfully', $categories);
    }

    /**
     * Get categories by parent
     */
    public function getByParent($parentId)
    {
        $categories = ComplaintCategory::where('parent_id', $parentId)
            ->where('status', SystemEnums::STATUS_ACTIVE)
            ->orderBy('name')
            ->get();

        return ApiResponse::success('Categories retrieved successfully', $categories);
    }

    /**
     * Get statistics
     */
    public function getStatistics()
    {
        $stats = Cache::remember('complaint_categories_statistics', 1800, function () {
            return [
                'total_categories' => ComplaintCategory::count(),
                'active_categories' => ComplaintCategory::where('status', SystemEnums::STATUS_ACTIVE)->count(),
                'inactive_categories' => ComplaintCategory::where('status', SystemEnums::STATUS_INACTIVE)->count(),
                'main_categories' => ComplaintCategory::whereNull('parent_id')->count(),
                'sub_categories' => ComplaintCategory::whereNotNull('parent_id')->count(),
                'categories_with_complaints' => ComplaintCategory::has('complaints')->count(),
                'categories_without_complaints' => ComplaintCategory::doesntHave('complaints')->count(),
                'recent_categories' => ComplaintCategory::where('created_at', '>=', now()->subDays(30))->count(),
                'last_updated' => now()->format('Y-m-d H:i:s'),
            ];
        });

        return ApiResponse::success('Statistics retrieved successfully', $stats);
    }

    /**
     * Get reports
     */
    public function getReports()
    {
        $reports = [
            'category_usage' => ComplaintCategory::withCount('complaints')
                ->orderBy('complaints_count', 'desc')
                ->limit(10)
                ->get(),
            'monthly_creation' => ComplaintCategory::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get(),
            'status_distribution' => ComplaintCategory::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'type_distribution' => ComplaintCategory::selectRaw('category_type, COUNT(*) as count')
                ->groupBy('category_type')
                ->get(),
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'generated_by' => auth()->user()->name ?? 'System',
        ];

        return ApiResponse::success('Reports generated successfully', $reports);
    }

    /**
     * Get usage report
     */
    public function getUsageReport()
    {
        $usageReport = ComplaintCategory::select('id', 'name', 'category_type', 'status')
            ->withCount(['complaints as total_complaints'])
            ->withCount(['complaints as active_complaints' => function ($query) {
                $query->where('status', 'active');
            }])
            ->withCount(['complaints as recent_complaints' => function ($query) {
                $query->where('created_at', '>=', now()->subDays(30));
            }])
            ->orderBy('total_complaints', 'desc')
            ->get()
            ->map(function ($category) {
                $category->usage_percentage = $category->total_complaints > 0 
                    ? round(($category->active_complaints / $category->total_complaints) * 100, 2) 
                    : 0;
                return $category;
            });

        $reportData = [
            'categories' => $usageReport,
            'summary' => [
                'total_categories' => $usageReport->count(),
                'categories_with_complaints' => $usageReport->where('total_complaints', '>', 0)->count(),
                'most_used_category' => $usageReport->first(),
                'report_date' => now()->format('Y-m-d H:i:s'),
            ]
        ];

        return ApiResponse::success('Usage report generated successfully', $reportData);
    }

    /**
     * Configuration-based DataTable (alternative implementation)
     */
    public function indexWithConfig(Request $request)
    {
        $config = config('datatable.models.complaint_categories');
        return DataTableService::createTable($request, $config['model'], $config)->response();
    }

    /**
     * Custom configuration on the fly
     */
    public function indexCustom(Request $request)
    {
        $helper = new DataTableHelper($request, ComplaintCategory::class);
        
        return $helper
            ->addJoin('complaint_categories as parents', 'complaint_categories.parent_id', '=', 'parents.id')
            ->mapColumn('parent_category', 'parents.name')
            ->setSearchableColumns(['name', 'slug', 'parent_category'])
            ->setSortableColumns(['id', 'name', 'category_type', 'parent_category', 'status', 'created_at'])
            ->setFilterableColumns(['id', 'name', 'slug', 'status', 'category_type', 'parent_id', 'parent_category'])
            ->addRenderer('parent_category', function ($item) {
                return $item->parent ? $item->parent->name : '-';
            })
            ->response();
    }

    /**
     * Private helper methods
     */
    private function wouldCreateCircularReference($categoryId, $parentId)
    {
        $currentParent = $parentId;
        $visited = [];

        while ($currentParent) {
            if ($currentParent == $categoryId) {
                return true;
            }

            if (in_array($currentParent, $visited)) {
                return true; // Circular reference detected
            }

            $visited[] = $currentParent;
            $parent = ComplaintCategory::find($currentParent);
            $currentParent = $parent ? $parent->parent_id : null;
        }

        return false;
    }

    private function clearCategoriesCache()
    {
        Cache::forget('complaint_categories_all');
        Cache::forget('complaint_categories_active');
        Cache::forget('complaint_categories_main');
        Cache::forget('complaint_categories_sub');
        Cache::forget('complaint_categories_statistics');
    }
}