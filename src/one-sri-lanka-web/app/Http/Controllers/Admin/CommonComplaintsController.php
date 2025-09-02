<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommonComplaintsController extends Controller
{
    public function index()
    {
        return view('admin.pages.complaints.common-complaints.index', [
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

        $complaintCategories = collect($response['data']['data'] ?? $response['data'] ?? [])->map(function ($item) {
            return (object) $item;
        });

        return view('admin.pages.complaints.common-complaints.create', [
            'title' => 'Common Complaints',
            'subtitle' => 'Create Common Complaint',
            'complaintCategories' => $complaintCategories,
        ]);
    }

    public function store()
    {
        $data = request()->validate([
            'complaint_category_id' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'form_data' => 'required|json',
        ]);

        $response = $this->api->post(
            env('ONE_SRI_LANKA_API_URL') . '/common-complaints',
            $data
        );

        if (isset($response['status']) && $response['status'] === 'error') {
            Log::info($response['response_body']);
            return redirect()->back()->with('error',$response['response_body']['message'])->withErrors($response['response_body']['errors']);
        }

        return redirect()->route('admin.common-complaints.index')->with('success', 'Common complaint created successfully.');
    }

    public function edit($id)
    {
        return view('admin.pages.complaints.common-complaints.edit', ['id' => $id]);
    }
}
