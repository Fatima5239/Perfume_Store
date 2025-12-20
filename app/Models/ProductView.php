<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductView extends Model
{
    use HasFactory;

    protected $table = 'product_views';
    protected $fillable = ['product_id', 'session_id', 'ip_address'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Get today's views
    public static function getTodayViews()
    {
        return self::whereDate('created_at', today())->count();
    }

    // Get most viewed products
    public static function getMostViewed($limit = 5)
    {
        return self::with('product.brand')
            ->select('product_id', \DB::raw('COUNT(*) as view_count'))
            ->whereDate('created_at', today())
            ->groupBy('product_id')
            ->orderByDesc('view_count')
            ->limit($limit)
            ->get();
    }
}