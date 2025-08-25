<?php

namespace App\Http\Controllers\Admin;

use App\Constants\SystemEnums;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $response = $this->api->get(
            env('ONE_SRI_LANKA_API_URL') . '/complaint-category',
            ['all' => 'true']
        );

        if (isset($response['status']) && $response['status'] === 'error') {
            return redirect()->back()->withErrors(['api_error' => $response['message']]);
        }

        $complaintCategories = collect($response['data'] ?? [])->map(function ($item) {
            return (object) $item;
        });

        return view('admin.pages.complaints.complaint-category.create', [
            'title' => 'Complaint Categories',
            'subtitle' => 'Create Complaint Category',
            'complaintCategories' => $complaintCategories
        ]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            // 'name'          => 'required|string|max:255',
            'parent_id'     => 'nullable|integer',
            'category_type' => 'required|string|max:100',
            'description'   => 'nullable|string',
            'status'        => 'required|in:' . SystemEnums::STATUS_ACTIVE . ',' . SystemEnums::STATUS_INACTIVE,
        ]);

        $response = $this->api->post(
            env('ONE_SRI_LANKA_API_URL') . '/complaint-category',
            $validated
        );

        return $response;

        // return redirect()->route('admin.complaint-category.index')
        //     ->with('success', 'Complaint category created successfully.');
    }

    public function edit($id)
    {
        return view('admin.pages.complaints.complaint-category.edit', ['id' => $id]);
    }
}
