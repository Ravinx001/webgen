<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        return view('admin.pages.complaints.common-complaints.create',[
            'title' => 'Common Complaints',
            'subtitle' => 'Create Common Complaint',
        ]);
    }

    public function edit($id)
    {
        return view('admin.pages.complaints.common-complaints.edit', ['id' => $id]);
    }
}
