<?php

namespace App\Services;

use App\Models\VisitorStat;
use App\Models\SearchStat;
use App\Models\ProductView;
use Illuminate\Support\Facades\Session;

class TrackingService
{
    // Track any page view
    public static function trackPageView($request, $pageType)
    {
        // Don't track admin pages
        if (str_starts_with($request->path(), 'admin')) return;

        VisitorStat::create([
            'session_id' => Session::getId(),
            'page_url' => $request->fullUrl(),
            'page_type' => $pageType,
            'device' => self::getDeviceType($request->userAgent()),
            'visited_at' => now(),
        ]);
    }

    // Track search (from collections page)
    public static function trackSearch($request, $results)
    {
        if (!$request->filled('q')) return;

        SearchStat::create([
            'search_query' => $request->input('q'),
            'results_count' => $results->count(),
            'session_id' => Session::getId(),
            'ip_address' => $request->ip(),
        ]);
    }

    // Track product view
    public static function trackProductView($productId)
    {
        ProductView::create([
            'product_id' => $productId,
            'session_id' => Session::getId(),
            'ip_address' => request()->ip(),
        ]);
    }

    private static function getDeviceType($userAgent)
    {
        $ua = strtolower($userAgent);
        if (strpos($ua, 'mobile') !== false) return 'mobile';
        if (strpos($ua, 'tablet') !== false) return 'tablet';
        return 'desktop';
    }
}