<?php

namespace App\Http\Controllers\Admin;

use App\Constants\SystemEnums;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ComplaintsCategoryController extends Controller
{
    public function index()
    {

        return view('admin.pages.complaints.complaint-category.index', [
            'title' => 'Common Complaints',
            'subtitle' => 'List of Common Complaints',
        ]);
    }

    public function create()
    {
        $complaintCategories = $this->getComplaintCategories(true);

        return view('admin.pages.complaints.complaint-category.create', [
            'title' => 'Complaint Categories',
            'subtitle' => 'Create Complaint Category',
            'complaintCategories' => $complaintCategories
        ]);
    }

    public function getComplaintCategories($all = false)
    {
        $response = $this->api->get(
            env('ONE_SRI_LANKA_API_URL') . '/complaint-category',
            ['all' => $all ? 'true' : 'false']
        );

        if (isset($response['status']) && $response['status'] === 'error') {
            return redirect()->back()->withErrors(['api_error' => $response['message']]);
        }

        $complaintCategories = collect($response['data']['data'] ?? $response['data'] ?? [])->map(function ($item) {
            return (object) $item;
        });

        return $complaintCategories;
    }

    public function get(Request $request)
    {
        $draw = intval($request->input('draw', 1));
        $start = intval($request->input('start', 0));
        $length = intval($request->input('length', 10));
        $columns = $request->input('columns', []);
        $order = $request->input('order', []);
        $searchValue = $request->input('search.value') ?? ''; // Handle null safely

        // Build API parameters
        $apiParams = $this->buildApiParams($start, $length, $searchValue, $columns, $order);

        // Make API call
        $response = $this->api->get(env('ONE_SRI_LANKA_API_URL') . '/complaint-category', $apiParams);

        // Handle API errors
        if (isset($response['status']) && $response['status'] === 'error') {
            return $this->errorResponse($draw, $response['message'] ?? 'API Error');
        }

        // Process response data
        $apiData = $response['data'] ?? [];
        $dataArr = isset($apiData['data']) && is_array($apiData['data'])
            ? array_values($apiData['data'])
            : [];

        $recordsTotal = isset($apiData['total']) ? intval($apiData['total']) : count($dataArr);

        // Transform data to objects
        $data = array_map(fn($item) => (object)$item, $dataArr);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data,
        ]);
    }

    private function buildApiParams(int $start, int $length, string $searchValue = '', array $columns = [], array $order = []): array
    {
        $page = intval($start / $length) + 1;

        $apiParams = [
            'per_page' => $length,
            'page' => $page,
        ];

        // Add search parameter
        if (!empty($searchValue)) {
            $apiParams['search'] = $searchValue;
        }

        // Add column filters
        $filters = $this->extractColumnFilters($columns);
        if (!empty($filters)) {
            $apiParams = array_merge($apiParams, $filters);
        }

        // Add sorting
        if (!empty($order)) {
            $orderColIdx = $order[0]['column'] ?? null;
            $orderDir = $order[0]['dir'] ?? 'asc';

            if ($orderColIdx !== null && isset($columns[$orderColIdx]['data'])) {
                $apiParams['sort_by'] = $columns[$orderColIdx]['data'];
                $apiParams['sort_dir'] = $orderDir;
            }
        }

        return $apiParams;
    }

    private function extractColumnFilters(array $columns): array
    {
        $filters = [];
        foreach ($columns as $col) {
            $colName = $col['data'] ?? null;
            $colSearch = $col['search']['value'] ?? null;
            if ($colName && !empty($colSearch)) {
                $filters[$colName] = $colSearch;
            }
        }
        return $filters;
    }

    private function errorResponse(int $draw, string $message)
    {
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => $message,
        ]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'parent_id'     => 'nullable|integer',
            'category_type' => 'required|string|max:100',
            'description'   => 'nullable|string',
            'status'        => 'required|in:' . SystemEnums::STATUS_ACTIVE . ',' . SystemEnums::STATUS_INACTIVE,
        ]);

        $response = $this->api->post(
            env('ONE_SRI_LANKA_API_URL') . '/complaint-category',
            $validated
        );

        if (isset($response['status']) && $response['status'] === 'error') {

            return redirect()->back()
                ->withInput()
                ->with('error', $response['response_body']['message'] ?? 'Failed to update category')
                ->withErrors($response['response_body']['errors'] ?? []);;
        }

        return redirect()->route('admin.complaint-category.index')
            ->with('success', 'Complaint category created successfully.');
    }

    public function edit($id)
    {
        $response = $this->api->get(
            env('ONE_SRI_LANKA_API_URL') . '/complaint-category/' . $id
        );

        if (isset($response['status']) && $response['status'] === 'error') {
            return redirect()->back()->withErrors(['api_error' => $response['message']]);
        }

        $complaintCategory = $response['data'] ?? null;

        $complaintCategories = $this->getComplaintCategories(true);
        return view('admin.pages.complaints.complaint-category.edit', ['id' => $id, 'complaintCategory' => $complaintCategory, 'complaintCategories' => $complaintCategories ?? []]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|min:6',
            'slug' => 'nullable|string|max:255',
            'parent_id' => 'nullable|integer',
            'category_type' => 'required|string|in:main,sub,civil_issue,public_service,infrastructure',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ]);

        $response = $this->api->post(
            env('ONE_SRI_LANKA_API_URL') . '/complaint-category/' . $id . '/update',
            $validatedData
        );

        if (isset($response['status']) && $response['status'] === 'error') {

            return redirect()->back()
                ->withInput()
                ->with('error', $response['response_body']['message'] ?? 'Failed to update category')
                ->withErrors($response['response_body']['errors'] ?? []);;
        }

        return redirect()->route('admin.complaint-category.index')
            ->with('success', 'Complaint category updated successfully!');
    }

    public function show() {}
}
