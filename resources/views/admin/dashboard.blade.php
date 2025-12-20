@extends('admin.layouts.app')

@section('page-title', 'Dashboard')

@section('content')
    <!-- Date Bar -->
    <div class="bg-black text-white rounded-xl p-4 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="text-center md:text-left">
                <h1 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }}!</h1>
                <p class="text-gray-300 mt-1">Real-time Store Analytics</p>
            </div>
            <div class="mt-3 md:mt-0">
                <span class="text-sm bg-gray-800 px-4 py-2 rounded-full font-medium">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    {{ date('l, F j, Y') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Key Statistics - REAL DATA -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Today's Visitors - REAL -->
        <div class="bg-white rounded-xl shadow p-6 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Today's Visitors</p>
                    <p class="text-3xl font-bold mt-2 text-gray-800">{{ $todayVisitors }}</p>
                    <p class="text-blue-600 text-sm mt-2">
                        <i class="fas fa-users mr-1"></i>
                        {{ $activeVisitors }} active now
                    </p>
                </div>
                <div class="bg-blue-50 text-blue-600 p-3 rounded-lg">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Today's Searches - REAL -->
        <div class="bg-white rounded-xl shadow p-6 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Today's Searches</p>
                    <p class="text-3xl font-bold mt-2 text-gray-800">{{ $todaySearches }}</p>
                    <p class="text-purple-600 text-sm mt-2">
                        <i class="fas fa-search mr-1"></i>
                        Search activity
                    </p>
                </div>
                <div class="bg-purple-50 text-purple-600 p-3 rounded-lg">
                    <i class="fas fa-search text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Product Views - REAL -->
        <div class="bg-white rounded-xl shadow p-6 border border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Product Views</p>
                    <p class="text-3xl font-bold mt-2 text-gray-800">{{ $todayViews }}</p>
                    <p class="text-green-600 text-sm mt-2">
                        <i class="fas fa-eye mr-1"></i>
                        Views today
                    </p>
                </div>
                <div class="bg-green-50 text-green-600 p-3 rounded-lg">
                    <i class="fas fa-perfume-bottle text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout - REAL DATA -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Searches - REAL DATA -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">
                <i class="fas fa-search mr-2"></i>Top Searches Today
            </h2>
            <div class="space-y-4">
                @forelse($topSearches as $index => $search)
                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg border border-gray-100">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-100 to-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="font-bold text-blue-600">#{{ $index + 1 }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $search->search_query }}</p>
                            <p class="text-sm text-gray-500">{{ $search->search_count }} searches</p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-search fa-3x mb-4 opacity-50"></i>
                    <p class="text-lg">No searches yet</p>
                    <p class="text-sm mt-2">Searches will appear here when users search</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Most Viewed Products - REAL DATA -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">
                <i class="fas fa-eye mr-2"></i>Most Viewed Products
            </h2>
            <div class="space-y-4">
                @forelse($mostViewed as $index => $view)
                <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg border border-gray-100">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-r from-green-100 to-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <span class="font-bold text-green-600">#{{ $index + 1 }}</span>
                        </div>
                        <div class="truncate">
                            <p class="font-medium text-gray-800 truncate">
                                {{ $view->product->name ?? 'Unknown Product' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $view->product->brand->name ?? 'No Brand' }}
                                • {{ $view->view_count }} views
                            </p>
                        </div>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-eye fa-3x mb-4 opacity-50"></i>
                    <p class="text-lg">No views yet</p>
                    <p class="text-sm mt-2">Product views will appear here</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Stats - REAL DATA -->
    <div class="mt-8 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Stats</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-white rounded-lg shadow-sm border">
                <p class="text-sm text-gray-600 mb-2">Mobile Visitors</p>
                <p class="text-2xl font-bold text-gray-800">{{ $mobilePercentage }}%</p>
                <p class="text-xs text-gray-500 mt-1">of all visitors</p>
            </div>
            <div class="text-center p-4 bg-white rounded-lg shadow-sm border">
                <p class="text-sm text-gray-600 mb-2">Active Now</p>
                <p class="text-2xl font-bold text-gray-800">{{ $activeVisitors }}</p>
                <p class="text-xs text-gray-500 mt-1">real-time</p>
            </div>
            <div class="text-center p-4 bg-white rounded-lg shadow-sm border">
                <p class="text-sm text-gray-600 mb-2">Total Activity</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $todayVisitors + $todaySearches + $todayViews }}
                </p>
                <p class="text-xs text-gray-500 mt-1">today</p>
            </div>
        </div>
    </div>

    <!-- Page Breakdown - NEW SECTION -->
    <div class="mt-8 bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">
            <i class="fas fa-chart-pie mr-2"></i>Today's Page Breakdown
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">All Collections</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $collectionViews ?? '0' }}
                </p>
                <p class="text-xs text-gray-500 mt-1">page views</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Women's</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $womenViews ?? '0' }}
                </p>
                <p class="text-xs text-gray-500 mt-1">page views</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Men's</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $menViews ?? '0' }}
                </p>
                <p class="text-xs text-gray-500 mt-1">page views</p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Unisex</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ $unisexViews ?? '0' }}
                </p>
                <p class="text-xs text-gray-500 mt-1">page views</p>
            </div>
        </div>
    </div>
@endsection