<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'no', 'name', 'slug', 'image', 'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'no' => 'integer'
    ];

    // Add default attributes
    protected $attributes = [
        'status' => true,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            // Generate slug from name
            $category->slug = Str::slug($category->name);
            
            // Set default status if not provided
            if (!isset($category->status)) {
                $category->status = true;
            }

            // Set sequential number if not provided
            if (!isset($category->no)) {
                $category->no = (Category::max('no') ?? 0) + 1;
            }
        });

        static::updating(function ($category) {
            // Update slug when name changes
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Add scope for active categories
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}