<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitorStat;
use App\Models\SearchStat;
use App\Models\ProductView;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            // Visitor Stats
            'activeVisitors' => VisitorStat::getActiveVisitors(),
            'todayVisitors' => VisitorStat::getTodayVisitors(),
            'mobilePercentage' => VisitorStat::getMobilePercentage(),
            
            // Search Stats
            'todaySearches' => SearchStat::getTodaySearches(),
            'topSearches' => SearchStat::getMostSearched(5),
            
            // Product View Stats
            'todayViews' => ProductView::getTodayViews(),
            'mostViewed' => ProductView::getMostViewed(5),
            
            // Page-specific views
            'collectionViews' => $this->getPageViews('collection'),
            'womenViews' => $this->getPageViews('collection_women'),
            'menViews' => $this->getPageViews('collection_men'),
            'unisexViews' => $this->getPageViews('collection_unisex'),
        ]);
    }
    
    private function getPageViews($pageType)
    {
        return VisitorStat::where('page_type', $pageType)
            ->whereDate('visited_at', today())
            ->count();
    }
}