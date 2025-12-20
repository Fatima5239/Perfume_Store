<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\TrackingService;

class HomeController extends Controller
{
    public function index()
    {
        // Track homepage visit
        TrackingService::trackPageView(request(), 'home');

        // Get featured products (8 random products for now)
        $featuredProducts = Product::where('status', 'active')
            ->where('available', true)
            ->inRandomOrder()
            ->limit(8)
            ->get();

        return view('home', [
            'featuredProducts' => $featuredProducts,
        ]);
    }
}