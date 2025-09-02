<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ComplaintFormStructure extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'complaint_category_id',
        'form_structure',
        'validation_rules',
        'status',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'form_structure' => 'array',
        'validation_rules' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
            $model->created_by = 'Ravinx-SLIIT';
            $model->updated_by = 'Ravinx-SLIIT';
        });

        static::updating(function ($model) {
            if ($model->isDirty('name')) {
                $model->slug = Str::slug($model->name);
            }
            $model->updated_by = 'Ravinx-SLIIT';
        });
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(ComplaintCategory::class, 'complaint_category_id');
    }

    // public function complaints()
    // {
    //     return $this->hasMany(Complaint::class, 'form_structure_id');
    // }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('complaint_category_id', $categoryId);
    }

    // Methods
    public function getFieldsAttribute()
    {
        return $this->form_structure['fields'] ?? [];
    }

    public function getFormConfigAttribute()
    {
        return $this->form_structure['config'] ?? [];
    }
}