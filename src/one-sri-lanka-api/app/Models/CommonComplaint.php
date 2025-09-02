<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommonComplaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_category_id',
        'title',
        'description',
        'form_data',
    ];

    /**
     * Relationship: A complaint belongs to a category
     */
    public function category()
    {
        return $this->belongsTo(ComplaintCategory::class, 'complaint_category_id');
    }
}
