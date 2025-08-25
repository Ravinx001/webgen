<?php

namespace App\Services;

use App\Models\ComplaintCategory;

class ComplaintService
{

    public function createComplaintCategory($data)
    {
        return ComplaintCategory::create($data);
    }

    public function getComplaintCategoryById($id)
    {
        return ComplaintCategory::findOrFail($id);
    }

    public function updateComplaintCategory($id, $data)
    {
        $category = $this->getComplaintCategoryById($id);
        $category->update($data);
        return $category;
    }

    public function updateComplaintCategoryStatus($id, $status)
    {
        $category = $this->getComplaintCategoryById($id);

        $category->status = $status;
        $category->save();

        return $category;
    }

    public function createCommonComplaintFormat($data)
    {

        // Logic to create a common complaint format
        return response()->json([
            'status' => 'success',
            'message' => 'Common complaint format created successfully',
            'data' => $data
        ]);
    }
}
