<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

protected $fillable = [
        'brand_id', 
        'name',
        'description', // Now nullable
        'fragrance_notes',
        'gender',
        'price_100ml', // Changed from 'price'
        'discount_100ml', // Changed from 'discount_price'
        'available', // Added: replaces 'stock'
        'main_image',
        'status',
    ];

   
  

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function fragrances()
    {
        return $this->belongsToMany(Fragrance::class, 'product_fragrance');
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}
