<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchStat extends Model
{
    use HasFactory;

    protected $table = 'search_stats';
    protected $fillable = ['search_query', 'results_count', 'session_id', 'ip_address'];

    // Get today's searches
    public static function getTodaySearches()
    {
        return self::whereDate('created_at', today())->count();
    }

    // Get most searched terms
    public static function getMostSearched($limit = 5)
    {
        return self::select('search_query', \DB::raw('COUNT(*) as search_count'))
            ->whereDate('created_at', today())
            ->groupBy('search_query')
            ->orderByDesc('search_count')
            ->limit($limit)
            ->get();
    }
}