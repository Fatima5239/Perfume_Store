<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorStat extends Model
{
    use HasFactory;

    protected $table = 'visitor_stats';
    protected $fillable = ['session_id', 'page_url', 'page_type', 'device', 'visited_at'];
    protected $casts = ['visited_at' => 'datetime'];

    // Get active visitors (last 5 minutes)
    public static function getActiveVisitors()
    {
        return self::where('visited_at', '>=', now()->subMinutes(5))
            ->distinct('session_id')->count();
    }

    // Get today's visitors
    public static function getTodayVisitors()
    {
        return self::whereDate('visited_at', today())
            ->distinct('session_id')->count();
    }

    // Get mobile percentage
    public static function getMobilePercentage()
    {
        $total = self::count();
        $mobile = self::where('device', 'mobile')->count();
        return $total > 0 ? round(($mobile / $total) * 100) : 0;
    }
}