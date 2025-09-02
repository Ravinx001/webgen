<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommonComplaint;
use Illuminate\Http\Request as HttpRequest;

class CommonComplaintController extends Controller
{
    public function __construct() {}

    public function store(HttpRequest $request)
    {

        $validated = $request->validate([
            'complaint_category_id' => 'required|exists:complaint_categories,id',
            'title'                 => 'required|string|max:255',
            'description'           => 'nullable|string',
            'form_data'             => 'nullable|json', 
        ]);

        $complaint = CommonComplaint::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Complaint created successfully.',
            'data'    => $complaint,
        ], 201);
    }


}