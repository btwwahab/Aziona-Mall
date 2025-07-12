<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'price',
        'discount_price',
        'description',
        'image_front',
        'image_back',
        'stock',
        'sku',
        'tags',
        'status',
        'is_hot_release'
    ];

    protected $attributes = [
        'status' => 1,
        'stock' => 0,
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'status' => 'boolean',
        'stock' => 'integer',
        'is_hot_release' => 'boolean'
    ];

    protected $appends = [
        'formatted_price',
        'discount_percentage',
        'image_front_url',
        'image_back_url'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (!$product->slug) {
                $product->slug = Str::slug($product->name);
            }

            if (!$product->sku) {
                $product->sku = $product->generateSKU();
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && !$product->isDirty('slug')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function views()
    {
        return $this->hasMany(ProductView::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function generateSKU()
    {
        if (!$this->category && $this->category_id) {
            $this->load('category');
        }

        $categoryCode = $this->category ? strtoupper(substr($this->category->name, 0, 3)) : 'GEN';
        $productCode = strtoupper(substr($this->name, 0, 3));
        $randomNumber = sprintf('%03d', rand(0, 999));

        return "{$categoryCode}-{$productCode}-{$randomNumber}";
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->discount_price && $this->price > 0) {
            return round(($this->price - $this->discount_price) / $this->price * 100);
        }
        return 0;
    }

    public function getImageFrontUrlAttribute()
    {
        return $this->image_front ? asset('storage/' . $this->image_front) : null;
    }

    public function getImageBackUrlAttribute()
    {
        return $this->image_back ? asset('storage/' . $this->image_back) : null;
    }

    public function getTagsArrayAttribute()
    {
        return $this->tags ? explode(',', $this->tags) : [];
    }

    public function isInStock()
    {
        return $this->stock > 0;
    }

    public function hasDiscount()
    {
        return $this->discount_price && $this->discount_price < $this->price;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeWithDiscount($query)
    {
        return $query->whereNotNull('discount_price')
            ->whereRaw('discount_price < price');
    }

    public function updateTrendingScore()
    {
        $viewWeight = 1;
        $salesWeight = 10;

        $viewCount = $this->views()->distinct('ip_address')->count('ip_address');

        $salesCount = $this->sales_count ?? 0;

        $score = ($viewCount * $viewWeight) + ($salesCount * $salesWeight);

        if ($score > 100) {
            $this->trending_score = now();
            $this->save();
        }
    }


    public function getDiscountBadgeAttribute()
    {
        if ($this->discount_price) {
            $discountPercentage = (($this->price - $this->discount_price) / $this->price) * 100;

            if ($discountPercentage >= 20) {
                return '<div class="product__badge light-red">💰 Deal ' . round($discountPercentage) . '% Off</div>';
            } elseif ($discountPercentage > 0 && $discountPercentage < 20) {
                return '<div class="product__badge light-pink">🔥 Hot</div>';
            }
        } elseif ($this->is_hot_release) {
            return '<div class="product__badge light-pink">🔥 Hot</div>';
        }

        return '<div class="product__badge light-orange">📦 Regular</div>';
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }



}