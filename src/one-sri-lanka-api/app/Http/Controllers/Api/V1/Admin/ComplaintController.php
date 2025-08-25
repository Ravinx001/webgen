<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Constants\SystemEnums;
use App\Facades\Complaint;
use App\Http\Controllers\Controller;
use App\Models\ComplaintCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ComplaintController extends Controller
{

    public function __construct()
    {
        // Constructor logic if needed
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status'); // active/inactive
        $categoryType = $request->get('category_type'); // e.g., product, service
        $parentId = $request->get('parent_id'); // for subcategories

        // Base query
        $query = ComplaintCategory::query();

        if ($request->get('all') === 'true') {
            $categories = $query->orderBy('name')->get(['id', 'name']);
            return $this->successResponse($categories, 'All categories retrieved successfully');
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('slug', 'like', '%' . $search . '%');
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($categoryType) {
            $query->where('category_type', $categoryType);
        }

        if ($parentId !== null) {
            $query->where('parent_id', $parentId);
        }

        // Pagination (default 10 per page)
        $perPage = $request->get('per_page', 10);
        $categories = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return $this->successResponse($categories, 'Complaint categories retrieved successfully');
    }

    public function storeComplaintCategory(Request $request)
    {

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'parent_id'     => 'nullable|exists:complaint_categories,id',
            'category_type' => 'required|string|max:100',
            'description'   => 'nullable|string',
            'status'        => 'nullable|in:' . SystemEnums::STATUS_ACTIVE . ',' . SystemEnums::STATUS_INACTIVE,
        ]);

        if (!isset($validated['status'])) {
            $validated['status'] = SystemEnums::STATUS_ACTIVE;
        }

        $category = Complaint::createComplaintCategory($validated);

        return $this->successResponse($category, 'Complaint category created successfully', 201);
    }
}
