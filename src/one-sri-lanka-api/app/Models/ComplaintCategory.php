<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ComplaintCategory extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'category_type',
        'description',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty('name')) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    // Parent relationship
    public function parent()
    {
        return $this->belongsTo(ComplaintCategory::class, 'parent_id');
    }

    // Children relationship
    public function children()
    {
        return $this->hasMany(ComplaintCategory::class, 'parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
