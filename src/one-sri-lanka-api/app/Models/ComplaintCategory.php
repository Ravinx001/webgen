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

    // Complaints relationship - Add this missing relationship
    // public function complaints()
    // {
    //     return $this->hasMany(Complaint::class, 'complaint_category_id');
    //     // Adjust the foreign key if it's different in your complaints table
    //     // Common variations: 'category_id', 'complaint_category_id', 'complaint_type_id'
    // }

    // Recursive children (descendants)
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    // Get all ancestors (parent chain)
    public function ancestors()
    {
        $ancestors = collect();
        $parent = $this->parent;
        
        while ($parent) {
            $ancestors->prepend($parent);
            $parent = $parent->parent;
        }
        
        return $ancestors;
    }

    // Check if this category has any descendants
    public function hasDescendants()
    {
        return $this->children()->exists();
    }

    // Get the root category
    public function root()
    {
        $current = $this;
        while ($current->parent) {
            $current = $current->parent;
        }
        return $current;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeMainCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeSubCategories($query)
    {
        return $this->whereNotNull('parent_id');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('category_type', $type);
    }

    public function scopeWithComplaintsCount($query)
    {
        return $query->withCount('complaints');
    }

    // Accessors
    public function getIsMainCategoryAttribute()
    {
        return is_null($this->parent_id);
    }

    public function getIsSubCategoryAttribute()
    {
        return !is_null($this->parent_id);
    }

    public function getComplaintsCountAttribute()
    {
        return $this->complaints()->count();
    }

    public function getFullNameAttribute()
    {
        if ($this->parent) {
            return $this->parent->name . ' > ' . $this->name;
        }
        return $this->name;
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = trim($value);
        
        // Auto-generate slug if it's empty
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }
}