{{-- resources/views/components/search-filter.blade.php --}}
@php
    // Configuration
    $route = $route ?? 'collections';
    $searchPlaceholder = $searchPlaceholder ?? 'Search perfumes...';
    $products = $products ?? collect();
    
    // Add unique identifier to prevent conflicts
    $componentId = $componentId ?? $route;

    // Calculate max price dynamically
    $maxPriceValue = 1000;
    if ($products->isNotEmpty()) {
        $maxPriceFromProducts = $products->max('price_100ml') ?? 1000;
        $maxPriceValue = max(1000, ceil($maxPriceFromProducts / 100) * 100);
    }

    // FIXED: Better route detection
    $isItemsPage = strpos(request()->path(), 'gifts') !== false;
    $isPerfumePage = !$isItemsPage;

    // Calculate active filter count
    $activeFilterCount = 0;
    if (request('min_price') || request('max_price'))
        $activeFilterCount++;
    if (request('sort') && request('sort') != 'newest')
        $activeFilterCount++;
    if (request('q'))
        $activeFilterCount++;
@endphp

@push('styles')
    <style>
        /* ALL YOUR EXISTING STYLES - EXACTLY THE SAME */
        /* ========== MAIN CONTAINER ========== */
        .collections-container {
            padding: 0;
        }

        /* ========== SUBTLE LOADING INDICATOR ========== */
        .loading-indicator {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #000 0%, #666 50%, #000 100%);
            background-size: 200% 100%;
            z-index: 2000;
            opacity: 0;
            transform: translateY(-100%);
            transition: opacity 0.3s, transform 0.3s;
            pointer-events: none;
        }

        .loading-indicator.active {
            opacity: 1;
            transform: translateY(0);
            animation: loadingSlide 1.5s ease-in-out infinite;
        }

        @keyframes loadingSlide {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        /* Quick loading pulse for buttons */
        .btn-loading {
            position: relative;
            overflow: hidden;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            animation: buttonShine 1s infinite;
        }

        @keyframes buttonShine {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        /* Skeleton Loaders - More Subtle */
        .skeleton-grid {
            padding: 15px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            opacity: 0.7;
            display: none;
        }

        @media (min-width: 1024px) {
            .skeleton-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 25px;
                padding: 0;
            }
        }

        @media (min-width: 768px) and (max-width: 1023px) {
            .skeleton-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        .skeleton-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .skeleton-image {
            height: 160px;
            background: linear-gradient(90deg, #f5f5f5 25%, #e8e8e8 37%, #f5f5f5 63%);
            background-size: 400% 100%;
            animation: skeletonPulse 1.5s ease-in-out infinite;
        }

        .skeleton-content {
            padding: 12px;
        }

        .skeleton-text {
            height: 12px;
            background: linear-gradient(90deg, #f5f5f5 25%, #e8e8e8 37%, #f5f5f5 63%);
            background-size: 400% 100%;
            border-radius: 4px;
            margin-bottom: 8px;
            animation: skeletonPulse 1.5s ease-in-out infinite;
        }

        .skeleton-text.short {
            width: 40%;
        }

        .skeleton-text.medium {
            width: 70%;
        }

        .skeleton-button {
            height: 32px;
            background: linear-gradient(90deg, #f5f5f5 25%, #e8e8e8 37%, #f5f5f5 63%);
            background-size: 400% 100%;
            border-radius: 6px;
            margin-top: 4px;
            animation: skeletonPulse 1.5s ease-in-out infinite;
        }

        @keyframes skeletonPulse {
            0% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0 50%;
            }
        }

        /* ========== SEARCH BAR (Sticky on Mobile) ========== */
        .search-bar {
            position: sticky;
            top: 0;
            background: white;
            z-index: 100;
            padding: 15px;
            border-bottom: 1px solid #eee;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .search-wrapper {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #000;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

        /* Quick loading state for search */
        .search-input.searching {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23999' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cline x1='12' y1='2' x2='12' y2='6'/%3E%3Cline x1='12' y1='18' x2='12' y2='22'/%3E%3Cline x1='4.93' y1='4.93' x2='7.76' y2='7.76'/%3E%3Cline x1='16.24' y1='16.24' x2='19.07' y2='19.07'/%3E%3Cline x1='2' y1='12' x2='6' y2='12'/%3E%3Cline x1='18' y1='12' x2='22' y2='12'/%3E%3Cline x1='4.93' y1='19.07' x2='7.76' y2='16.24'/%3E%3Cline x1='16.24' y1='7.76' x2='19.07' y2='4.93'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
        }

        .filter-toggle-btn {
            width: 44px;
            height: 44px;
            background: #000;
            color: white;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 18px;
            flex-shrink: 0;
            position: relative;
            transition: all 0.2s ease;
        }

        .filter-toggle-btn:hover {
            background: #333;
            transform: translateY(-1px);
        }

        .filter-toggle-btn:active {
            transform: translateY(0);
        }

        .filter-toggle-btn .filter-icon {
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .filter-toggle-btn .filter-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ff4444;
            color: white;
            font-size: 11px;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            font-weight: 600;
        }

        /* ========== ACTIVE FILTERS BAR ========== */
        .active-filters-bar {
            padding: 12px 15px;
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
            overflow-x: auto;
            white-space: nowrap;
            scrollbar-width: none;
        }

        .active-filters-bar::-webkit-scrollbar {
            display: none;
        }

        .filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 20px;
            font-size: 13px;
            color: #333;
            flex-shrink: 0;
        }

        .remove-filter-btn {
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            padding: 0;
            font-size: 16px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .clear-all-btn {
            margin-left: auto;
            color: #0066cc;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            flex-shrink: 0;
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px 10px;
        }

        /* ========== RESULTS HEADER ========== */
        .results-header {
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
        }

        .results-count {
            font-size: 14px;
            color: #666;
        }

        .sort-select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
            font-size: 14px;
            cursor: pointer;
        }

        /* ========== MOBILE FILTER DRAWER ========== */
        .filter-drawer-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .filter-drawer-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .filter-drawer {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: 320px;
            max-width: 90vw;
            background: white;
            z-index: 1001;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.1);
        }

        .filter-drawer.active {
            transform: translateX(0);
        }

        .drawer-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
        }

        .drawer-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .close-drawer-btn {
            background: none;
            border: none;
            font-size: 24px;
            color: #666;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background 0.2s;
        }

        .close-drawer-btn:hover {
            background: #eee;
        }

        .drawer-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            padding-bottom: 0;
            -webkit-overflow-scrolling: touch;
        }

        /* Filter Group */
        .price-range-group {
            margin-bottom: 25px;
        }

        .filter-group-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* ========== PRICE RANGE FILTER ========== */
        .price-range-inputs {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .price-input-wrapper {
            flex: 1;
        }

        .price-input-wrapper label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .price-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            background: white;
        }

        .price-input:focus {
            outline: none;
            border-color: #000;
        }

        .price-separator {
            font-size: 14px;
            color: #666;
            margin-top: 24px;
        }

        /* Price Range Slider */
        .price-range-container {
            margin: 25px 0;
        }

        .price-range-slider-container {
            position: relative;
            height: 40px;
            margin-bottom: 20px;
        }

        .price-range-slider-track {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 4px;
            background: #ddd;
            border-radius: 2px;
            transform: translateY(-50%);
        }

        .price-range-slider-track-fill {
            position: absolute;
            top: 50%;
            height: 4px;
            background: #000;
            border-radius: 2px;
            transform: translateY(-50%);
        }

        .price-range-slider-handle {
            position: absolute;
            top: 50%;
            width: 24px;
            height: 24px;
            background: #000;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
            transform: translate(-50%, -50%);
            cursor: pointer;
            z-index: 2;
            touch-action: none;
        }

        .price-range-labels {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }

        .price-range-values {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: 600;
            margin-top: 15px;
            color: #000;
        }

        /* Drawer Footer */
        .drawer-footer {
            padding: 20px;
            border-top: 1px solid #eee;
            background: white;
            display: flex;
            gap: 10px;
            position: sticky;
            bottom: 0;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
        }

        .apply-btn,
        .reset-btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            font-size: 15px;
        }

        .apply-btn {
            background: #000;
            color: white;
        }

        .apply-btn:hover {
            background: #333;
        }

        .reset-btn {
            background: #f5f5f5;
            color: #333;
            border: 1px solid #ddd;
        }

        .reset-btn:hover {
            background: #e9e9e9;
        }

        /* ========== PRODUCTS GRID ========== */
        .products-grid {
            padding: 15px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: transform 0.2s;
            position: relative;
        }

        .product-card:active {
            transform: scale(0.98);
        }

        .product-image {
            height: 160px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-info {
            padding: 12px;
        }

        .product-brand {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .product-name {
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 8px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .current-price {
            font-size: 15px;
            font-weight: 700;
            color: #000;
        }

        .original-price {
            font-size: 12px;
            color: #999;
            text-decoration: line-through;
        }

        .sale-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #ff4444;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }

        .view-product-btn {
            display: block;
            width: 100%;
            padding: 8px;
            background: #000;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            text-align: center;
            transition: background 0.3s;
            border: none;
            cursor: pointer;
        }

        .view-product-btn:hover {
            background: #333;
        }

        /* ========== DESKTOP STYLES ========== */
        @media (min-width: 1024px) {
            .collections-container {
                display: flex;
                gap: 30px;
                max-width: 1400px;
                margin: 0 auto;
                padding: 30px;
            }

            .filter-sidebar {
                flex: 0 0 280px;
                position: sticky;
                top: 30px;
                height: fit-content;
            }

            .filter-toggle-btn {
                display: none;
            }

            .search-bar {
                position: static;
                padding: 0;
                border: none;
                box-shadow: none;
                margin-bottom: 20px;
            }

            .search-wrapper {
                max-width: 600px;
            }

            .active-filters-bar {
                display: none;
            }

            .price-range-group {
                background: white;
                border: 1px solid #e0e0e0;
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 20px;
            }

            .products-area {
                flex: 1;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 25px;
                padding: 0;
            }

            .product-image {
                height: 220px;
            }

            .product-name {
                font-size: 15px;
            }

            .price-range-inputs {
                gap: 15px;
            }

            .skeleton-image {
                height: 220px;
            }
        }

        /* ========== TABLET STYLES ========== */
        @media (min-width: 768px) and (max-width: 1023px) {
            .collections-container {
                flex-direction: column;
            }

            .filter-sidebar {
                display: none;
            }

            .filter-toggle-btn {
                display: flex;
            }

            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }

            .product-image {
                height: 180px;
            }

            .skeleton-image {
                height: 180px;
            }
        }

        /* ========== MOBILE STYLES ========== */
        @media (max-width: 767px) {
            .collections-container {
                flex-direction: column;
            }

            .filter-sidebar {
                display: none;
            }

            .filter-toggle-btn {
                display: flex;
            }

            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .product-image {
                height: 160px;
            }
        }

        /* ========== SMALL PHONES ========== */
        @media (max-width: 374px) {
            .products-grid {
                grid-template-columns: 1fr;
            }

            .skeleton-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ========== SWIPE GESTURES ========== */
        .swipe-indicator {
            width: 40px;
            height: 4px;
            background: #ddd;
            border-radius: 2px;
            margin: 10px auto;
            display: none;
        }

        @media (max-width: 1023px) {
            .swipe-indicator {
                display: block;
            }
        }

        /* ========== NO RESULTS ========== */
        .no-results {
            text-align: center;
            padding: 60px 20px;
        }

        .no-results-icon {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .no-results h3 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }

        .no-results p {
            color: #666;
            margin-bottom: 20px;
        }

        /* ========== ITEMS PAGE SPECIFIC STYLES ========== */
        /* Gift items badge styling */
        .gift-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 2;
            box-shadow: 0 2px 8px rgba(255, 107, 107, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Item description styling */
        .item-description {
            font-size: 13px;
            color: #666;
            margin: 8px 0 12px 0;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Gift item specific image fallback */
        .gift-image-fallback {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gift-image-fallback i {
            font-size: 40px;
            color: #adb5bd;
        }

        /* Gift items brand styling */
        .gift-brand {
            color: #ff6b6b !important;
            font-weight: 600 !important;
        }

        /* Price styling for gift items */
        .gift-price {
            color: #495057;
            font-weight: 700;
        }

        /* No results styling for gift items */
        .gift-no-results .no-results-icon {
            color: #ff6b6b;
        }

        /* Gift items specific sale badge */
        .gift-sale-badge {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e) !important;
            color: white !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
        }

        /* Gift items card hover effect */
        .product-card.gift-item:hover {
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.15);
            border-color: #ff6b6b;
        }
        
        /* Special styling for gift items button */
        .gift-view-btn {
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e) !important;
            color: white !important;
            border: none !important;
            transition: all 0.3s ease !important;
        }

        .gift-view-btn:hover {
            background: linear-gradient(135deg, #ff5252, #ff6b6b) !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
        }
    </style>
@endpush

<div class="collections-container" data-component-id="{{ $componentId }}">
    <!-- Subtle Loading Indicator (Under Navbar) -->
    <div class="loading-indicator" id="loadingIndicator-{{ $componentId }}"></div>

    <!-- Desktop Sidebar (Hidden on Mobile) -->
    <aside class="filter-sidebar">
        <form method="GET" action="{{ route($route) }}" id="desktopFilterForm-{{ $componentId }}">
            @if(request('q'))
                <input type="hidden" name="q" value="{{ e(request('q')) }}">
            @endif

            <!-- Price Range Filter -->
            <div class="price-range-group">
                <h3 class="filter-group-title">Price Range</h3>

                <!-- Price Inputs -->
                <div class="price-range-inputs">
                    <div class="price-input-wrapper">
                        <label for="min_price-{{ $componentId }}">From</label>
                        <input type="number" name="min_price" id="min_price-{{ $componentId }}" class="price-input"
                            placeholder="Min" value="{{ request('min_price') }}" min="0" step="1"
                            onchange="updatePriceFromInput('desktop', '{{ $componentId }}')">
                    </div>
                    <div class="price-separator">–</div>
                    <div class="price-input-wrapper">
                        <label for="max_price-{{ $componentId }}">To</label>
                        <input type="number" name="max_price" id="max_price-{{ $componentId }}" class="price-input"
                            placeholder="Max" value="{{ request('max_price', $maxPriceValue) }}" min="0" step="1"
                            onchange="updatePriceFromInput('desktop', '{{ $componentId }}')">
                    </div>
                </div>

                <!-- Price Range Slider -->
                <div class="price-range-container">
                    <div class="price-range-slider-container">
                        <div class="price-range-slider-track"></div>
                        <div class="price-range-slider-track-fill" id="desktopPriceTrack-{{ $componentId }}"></div>
                        <div class="price-range-slider-handle" id="desktopMinHandle-{{ $componentId }}" style="left: 0%;"
                            data-value="0">
                        </div>
                        <div class="price-range-slider-handle" id="desktopMaxHandle-{{ $componentId }}" style="left: 100%;"
                            data-value="{{ $maxPriceValue }}"></div>
                    </div>
                    <div class="price-range-labels">
                        <span>$0</span>
                        <span>${{ $maxPriceValue }}</span>
                    </div>
                    <div class="price-range-values">
                        <span>$<span id="desktopMinDisplay-{{ $componentId }}">0</span></span>
                        <span>$<span id="desktopMaxDisplay-{{ $componentId }}">{{ $maxPriceValue }}</span></span>
                    </div>
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button type="submit" class="apply-btn" id="desktopApplyBtn-{{ $componentId }}" style="flex: 1;">Apply
                    Filters</button>
                <button type="button" class="reset-btn" onclick="clearAllFilters('{{ $route }}', '{{ $componentId }}')"
                    style="text-align: center; padding: 14px;">Clear All</button>
            </div>
        </form>
    </aside>

    <!-- Main Content Area -->
    <div class="products-area">
        <!-- Search Bar -->
        <div class="search-bar">
            <form method="GET" action="{{ route($route) }}" id="searchForm-{{ $componentId }}">
                <div class="search-wrapper">
                    <input type="text" name="q" value="{{ e(request('q', '')) }}" placeholder="{{ $searchPlaceholder }}"
                        class="search-input" id="searchInput-{{ $componentId }}" maxlength="100" aria-label="Search perfumes"
                        autocomplete="off">
                    <button type="button" class="filter-toggle-btn" id="mobileFilterToggle-{{ $componentId }}"
                        aria-label="Open filters">
                        <span class="filter-icon">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="4" width="18" height="2" rx="1" fill="white" />
                                <circle cx="7" cy="5" r="2" fill="white" />
                                <rect x="3" y="11" width="18" height="2" rx="1" fill="white" />
                                <circle cx="17" cy="12" r="2" fill="white" />
                                <rect x="3" y="18" width="18" height="2" rx="1" fill="white" />
                                <circle cx="12" cy="19" r="2" fill="white" />
                            </svg>
                        </span>
                        @if($activeFilterCount > 0)
                            <span class="filter-count">{{ $activeFilterCount }}</span>
                        @endif
                    </button>
                </div>
            </form>
        </div>

        <!-- Active Filters (Mobile) -->
        @php
            $hasActiveFilters = request()->hasAny(['min_price', 'max_price', 'sort']) || request('q');
        @endphp
        @if($hasActiveFilters)
            <div class="active-filters-bar">
                @if(request('q'))
                    <span class="filter-tag">
                        "{{ e(request('q')) }}"
                        <button class="remove-filter-btn" onclick="removeFilter('q', '{{ $route }}', '{{ $componentId }}')">×</button>
                    </span>
                @endif

                @if(request('min_price') || request('max_price'))
                    @php
                        $minPrice = request('min_price', 0);
                        $maxPrice = request('max_price', $maxPriceValue . '+');
                    @endphp
                    <span class="filter-tag">
                        ${{ $minPrice }} - ${{ $maxPrice }}
                        <button class="remove-filter-btn" onclick="removePriceFilter('{{ $route }}', '{{ $componentId }}')">×</button>
                    </span>
                @endif

                @if(request('sort') && request('sort') != 'newest')
                    <span class="filter-tag">
                        {{ ucfirst(str_replace('_', ' ', e(request('sort')))) }}
                        <button class="remove-filter-btn" onclick="removeFilter('sort', '{{ $route }}', '{{ $componentId }}')">×</button>
                    </span>
                @endif

                <button onclick="clearAllFilters('{{ $route }}', '{{ $componentId }}')" class="clear-all-btn">Clear all</button>
            </div>
        @endif

        <!-- Results Header -->
        <div class="results-header">
            <div class="results-count">
                @if(request('q'))
                    @if(isset($products) && $products->total())
                        {{ $products->total() }} result{{ $products->total() !== 1 ? 's' : '' }}
                    @endif
                @else
                    @if(isset($products) && $products->total())
                        {{ $products->total() }} product{{ $products->total() !== 1 ? 's' : '' }}
                    @endif
                @endif
            </div>

            <select name="sort" class="sort-select" onchange="updateSort(this.value, '{{ $route }}', '{{ $componentId }}')">
                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A-Z</option>
            </select>
        </div>

        <!-- Skeleton Loader (hidden by default) -->
        <div class="skeleton-grid" id="skeletonLoader-{{ $componentId }}">
            @for($i = 0; $i < 6; $i++)
                <div class="skeleton-card">
                    <div class="skeleton-image"></div>
                    <div class="skeleton-content">
                        <div class="skeleton-text short"></div>
                        <div class="skeleton-text medium"></div>
                        <div class="skeleton-button"></div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- Products Grid -->
        @php
            $hasProducts = isset($products) && $products->count() > 0;
        @endphp
        
        @if($hasProducts)
            <div class="products-grid" id="productsGrid-{{ $componentId }}">
                @foreach($products as $product)
                    <div class="product-card">
                        {{-- Different badges for items vs perfumes --}}
                        @if($isItemsPage)
                            <div class="sale-badge" style="background: #ff6b6b;">
                                🎁 ITEM
                            </div>
                        @elseif($product->discount_100ml && $product->price_100ml > 0)
                            <div class="sale-badge">
                                SALE
                            </div>
                        @endif

                        <div class="product-image">
                            @if($product->main_image)
                                <img src="{{ asset('storage/' . e($product->main_image)) }}" alt="{{ e($product->name) }}"
                                    loading="lazy">
                            @else
                                <img src="{{ $isItemsPage ? 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf' : 'https://images.unsplash.com/photo-1585123334904-89d6f8e5f7b1' }}"
                                    alt="{{ e($product->name) }}" loading="lazy">
                            @endif
                        </div>

                        <div class="product-info">
                            <div class="product-brand">{{ e($product->brand->name ?? ($isItemsPage ? 'Gift Item' : 'Brand')) }}
                            </div>
                            <div class="product-name">{{ e($product->name) }}</div>

                            <div class="product-price">
                                @if(!$isItemsPage && $product->discount_100ml)
                                    <span class="current-price">${{ number_format($product->discount_100ml, 2) }}</span>
                                    <span class="original-price">${{ number_format($product->price_100ml, 2) }}</span>
                                @else
                                    <span class="current-price">${{ number_format($product->price_100ml, 2) }}</span>
                                @endif
                            </div>

                            {{-- FIXED: All products use View Product button with product.show route --}}
                            @if($isItemsPage)
                                {{-- For gift items --}}
                                <a href="{{ route('product.show', $product->id) }}" 
                                   class="view-product-btn gift-view-btn">
                                    View Product
                                </a>
                            @else
                                {{-- For perfumes --}}
                                <a href="{{ route('product.show', $product->id) }}" class="view-product-btn">
                                    View Product
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div style="padding: 20px 15px; text-align: center;">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif
        @else
            <div style="text-align: center; padding: 60px 20px;">
                <div style="font-size: 48px; color: #ddd; margin-bottom: 20px;">
                    {{ $isItemsPage ? '🎁' : '👃' }}
                </div>
                <h3 style="font-size: 18px; margin-bottom: 10px; color: #333;">
                    {{ $isItemsPage ? 'No gift items found' : 'No perfumes found' }}
                </h3>
                <p style="color: #666; margin-bottom: 20px;">Try adjusting your search or filters</p>
                <button onclick="clearAllFilters('{{ $route }}', '{{ $componentId }}')"
                    style="display: inline-block; padding: 12px 24px; background: #000; color: white; text-decoration: none; border-radius: 8px; font-weight: 500; border: none; cursor: pointer;">
                    Clear All Filters
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Mobile Filter Drawer -->
<div class="filter-drawer-overlay" id="filterOverlay-{{ $componentId }}"></div>
<div class="filter-drawer" id="filterDrawer-{{ $componentId }}">
    <div class="swipe-indicator"></div>
    <div class="drawer-header">
        <div class="drawer-title">Filter Products</div>
        <button class="close-drawer-btn" id="closeDrawer-{{ $componentId }}">&times;</button>
    </div>

    <form method="GET" action="{{ route($route) }}" id="mobileFilterForm-{{ $componentId }}">
        <div class="drawer-content">
            @if(request('q'))
                <input type="hidden" name="q" value="{{ e(request('q')) }}">
            @endif

            <!-- Price Range Filter (Mobile) -->
            <div class="price-range-group">
                <h3 class="filter-group-title">Price Range</h3>

                <div class="price-range-inputs">
                    <div class="price-input-wrapper">
                        <label for="mobile_min_price-{{ $componentId }}">From</label>
                        <input type="number" name="min_price" id="mobile_min_price-{{ $componentId }}" class="price-input"
                            placeholder="Min" value="{{ request('min_price') }}" min="0" step="1"
                            onchange="updatePriceFromInput('mobile', '{{ $componentId }}')">
                    </div>
                    <div class="price-separator">–</div>
                    <div class="price-input-wrapper">
                        <label for="mobile_max_price-{{ $componentId }}">To</label>
                        <input type="number" name="max_price" id="mobile_max_price-{{ $componentId }}" class="price-input"
                            placeholder="Max" value="{{ request('max_price', $maxPriceValue) }}" min="0" step="1"
                            onchange="updatePriceFromInput('mobile', '{{ $componentId }}')">
                    </div>
                </div>

                <!-- Price Range Slider for Mobile -->
                <div class="price-range-container">
                    <div class="price-range-slider-container">
                        <div class="price-range-slider-track"></div>
                        <div class="price-range-slider-track-fill" id="mobilePriceTrack-{{ $componentId }}"></div>
                        <div class="price-range-slider-handle" id="mobileMinHandle-{{ $componentId }}" style="left: 0%;"
                            data-value="0">
                        </div>
                        <div class="price-range-slider-handle" id="mobileMaxHandle-{{ $componentId }}" style="left: 100%;"
                            data-value="{{ $maxPriceValue }}"></div>
                    </div>
                    <div class="price-range-labels">
                        <span>$0</span>
                        <span>${{ $maxPriceValue }}</span>
                    </div>
                    <div class="price-range-values">
                        <span>$<span id="mobileMinDisplay-{{ $componentId }}">0</span></span>
                        <span>$<span id="mobileMaxDisplay-{{ $componentId }}">{{ $maxPriceValue }}</span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="drawer-footer">
            <button type="button" class="apply-btn" id="mobileApplyBtn-{{ $componentId }}"
                onclick="applyMobileFilters('{{ $componentId }}')">Apply Filters</button>
            <button type="button" class="reset-btn" onclick="clearAllFilters('{{ $route }}', '{{ $componentId }}')">Reset All</button>
        </div>
    </form>
</div>

@push('scripts')
    <script>
        // ========== SEARCH FILTER MANAGER ==========
        const SearchFilter = (function() {
            // Private variables
            const instances = new Map();
            let activeFilterManager = null;

            // Price Slider Manager
            class PriceSlider {
                constructor(componentId, type) {
                    this.componentId = componentId;
                    this.type = type;
                    this.isDragging = false;
                    this.activeHandle = null;
                    this.maxPrice = 1000;
                    this.init();
                }

                init() {
                    this.minHandle = document.getElementById(`${this.type}MinHandle-${this.componentId}`);
                    this.maxHandle = document.getElementById(`${this.type}MaxHandle-${this.componentId}`);
                    
                    if (!this.minHandle || !this.maxHandle) return;
                    
                    // Get max price from data attribute
                    this.maxPrice = parseInt(this.maxHandle.getAttribute('data-value')) || 1000;
                    
                    this.setupInitialValues();
                    this.bindEvents();
                }

                setupInitialValues() {
                    const minInput = this.getInput('min');
                    const maxInput = this.getInput('max');
                    
                    const minValue = parseInt(minInput.value) || 0;
                    const maxValue = parseInt(maxInput.value) || this.maxPrice;
                    
                    this.updateUI(minValue, maxValue);
                }

                getInput(which) {
                    const prefix = this.type === 'desktop' ? '' : 'mobile_';
                    return document.getElementById(`${prefix}${which}_price-${this.componentId}`);
                }

                bindEvents() {
                    // Remove existing listeners by cloning
                    const newMinHandle = this.minHandle.cloneNode(true);
                    const newMaxHandle = this.maxHandle.cloneNode(true);
                    this.minHandle.parentNode.replaceChild(newMinHandle, this.minHandle);
                    this.maxHandle.parentNode.replaceChild(newMaxHandle, this.maxHandle);
                    
                    this.minHandle = newMinHandle;
                    this.maxHandle = newMaxHandle;

                    [this.minHandle, this.maxHandle].forEach(handle => {
                        handle.addEventListener('mousedown', (e) => this.startDrag(e));
                        handle.addEventListener('touchstart', (e) => this.startDragTouch(e));
                    });
                }

                startDrag(e) {
                    e.preventDefault();
                    this.isDragging = true;
                    this.activeHandle = e.target;
                    activeFilterManager = this;

                    document.addEventListener('mousemove', this.drag);
                    document.addEventListener('mouseup', this.stopDrag);
                }

                startDragTouch(e) {
                    e.preventDefault();
                    this.isDragging = true;
                    this.activeHandle = e.target;
                    activeFilterManager = this;

                    document.addEventListener('touchmove', this.dragTouch);
                    document.addEventListener('touchend', this.stopDrag);
                }

                drag = (e) => {
                    if (!this.isDragging || !this.activeHandle) return;

                    const sliderContainer = this.activeHandle.closest('.price-range-slider-container');
                    const rect = sliderContainer.getBoundingClientRect();
                    const clientX = e.clientX;

                    let percent = ((clientX - rect.left) / rect.width) * 100;
                    percent = Math.max(0, Math.min(100, percent));

                    const value = Math.round((percent / 100) * this.maxPrice);

                    const minValue = parseInt(this.minHandle.getAttribute('data-value'));
                    const maxValue = parseInt(this.maxHandle.getAttribute('data-value'));

                    if (this.activeHandle.id.includes('Min')) {
                        const newMin = Math.min(value, maxValue);
                        this.updateHandle(this.minHandle, newMin);
                        this.updateUI(newMin, maxValue);
                    } else {
                        const newMax = Math.max(value, minValue);
                        this.updateHandle(this.maxHandle, newMax);
                        this.updateUI(minValue, newMax);
                    }

                    // Auto-submit desktop form
                    if (this.type === 'desktop') {
                        setTimeout(() => {
                            this.submitDesktopForm();
                        }, 300);
                    }
                }

                dragTouch = (e) => {
                    if (e.touches && e.touches[0]) {
                        this.drag(e.touches[0]);
                    }
                }

                stopDrag = () => {
                    this.isDragging = false;
                    this.activeHandle = null;
                    activeFilterManager = null;

                    document.removeEventListener('mousemove', this.drag);
                    document.removeEventListener('mouseup', this.stopDrag);
                    document.removeEventListener('touchmove', this.dragTouch);
                    document.removeEventListener('touchend', this.stopDrag);
                }

                updateHandle(handle, value) {
                    const percent = (value / this.maxPrice) * 100;
                    handle.style.left = percent + '%';
                    handle.setAttribute('data-value', value);
                }

                updateUI(minValue, maxValue) {
                    const minPercent = (minValue / this.maxPrice) * 100;
                    const maxPercent = (maxValue / this.maxPrice) * 100;
                    
                    const priceTrack = document.getElementById(`${this.type}PriceTrack-${this.componentId}`);
                    const minDisplay = document.getElementById(`${this.type}MinDisplay-${this.componentId}`);
                    const maxDisplay = document.getElementById(`${this.type}MaxDisplay-${this.componentId}`);
                    const minInput = this.getInput('min');
                    const maxInput = this.getInput('max');

                    if (priceTrack) {
                        priceTrack.style.left = minPercent + '%';
                        priceTrack.style.width = (maxPercent - minPercent) + '%';
                    }

                    if (minDisplay) minDisplay.textContent = minValue;
                    if (maxDisplay) maxDisplay.textContent = maxValue;
                    if (minInput) minInput.value = minValue;
                    if (maxInput) maxInput.value = maxValue;
                }

                updateFromInput() {
                    const minInput = this.getInput('min');
                    const maxInput = this.getInput('max');
                    
                    let minValue = parseInt(minInput.value) || 0;
                    let maxValue = parseInt(maxInput.value) || this.maxPrice;

                    if (minValue > maxValue) {
                        minValue = maxValue;
                        minInput.value = minValue;
                    }

                    minValue = Math.max(0, Math.min(this.maxPrice, minValue));
                    maxValue = Math.max(0, Math.min(this.maxPrice, maxValue));

                    this.updateUI(minValue, maxValue);

                    if (this.type === 'desktop') {
                        setTimeout(() => {
                            this.submitDesktopForm();
                        }, 500);
                    }
                }

                submitDesktopForm() {
                    const form = document.getElementById(`desktopFilterForm-${this.componentId}`);
                    const applyBtn = document.getElementById(`desktopApplyBtn-${this.componentId}`);
                    
                    if (form && applyBtn) {
                        showLoading(this.componentId);
                        applyBtn.classList.add('btn-loading');
                        setTimeout(() => form.submit(), 300);
                    }
                }
            }

            // Main Filter Instance
            class FilterInstance {
                constructor(componentId, route) {
                    this.componentId = componentId;
                    this.route = route;
                    this.searchTimeout = null;
                    this.loadingTimeout = null;
                    this.priceSliders = {};
                    this.init();
                }

                init() {
                    // Initialize price sliders
                    this.priceSliders.desktop = new PriceSlider(this.componentId, 'desktop');
                    
                    // Setup search input
                    this.setupSearchInput();
                    
                    // Setup mobile drawer
                    this.setupMobileDrawer();
                    
                    // Auto-hide loading
                    this.autoHideLoading();
                }

                setupSearchInput() {
                    const searchInput = document.getElementById(`searchInput-${this.componentId}`);
                    if (!searchInput) return;

                    searchInput.addEventListener('input', () => {
                        clearTimeout(this.searchTimeout);

                        searchInput.classList.add('searching');

                        this.searchTimeout = setTimeout(() => {
                            searchInput.classList.remove('searching');
                            this.showLoading();
                            searchInput.form.submit();
                        }, 500);
                    });
                }

                setupMobileDrawer() {
                    const mobileFilterToggle = document.getElementById(`mobileFilterToggle-${this.componentId}`);
                    const filterOverlay = document.getElementById(`filterOverlay-${this.componentId}`);
                    const closeDrawer = document.getElementById(`closeDrawer-${this.componentId}`);
                    const filterDrawer = document.getElementById(`filterDrawer-${this.componentId}`);

                    if (mobileFilterToggle) {
                        mobileFilterToggle.addEventListener('click', () => this.openFilterDrawer());
                    }

                    if (filterOverlay) {
                        filterOverlay.addEventListener('click', () => this.closeFilterDrawer());
                    }

                    if (closeDrawer) {
                        closeDrawer.addEventListener('click', () => this.closeFilterDrawer());
                    }

                    // Touch gestures for mobile
                    if (filterDrawer) {
                        let startX = 0;
                        let currentX = 0;
                        let isDragging = true;

                        filterDrawer.addEventListener('touchstart', (e) => {
                            startX = e.touches[0].clientX;
                            currentX = startX;
                            isDragging = true;
                            filterDrawer.style.transition = 'none';
                        });

                        filterDrawer.addEventListener('touchmove', (e) => {
                            if (!isDragging) return;
                            currentX = e.touches[0].clientX;
                            const diff = startX - currentX;
                            if (diff < 0) return;
                            filterDrawer.style.transform = `translateX(${-diff}px)`;
                            filterOverlay.style.opacity = 1 - (diff / 320);
                        });

                        filterDrawer.addEventListener('touchend', () => {
                            if (!isDragging) return;
                            isDragging = false;
                            filterDrawer.style.transition = 'transform 0.3s ease';
                            filterOverlay.style.transition = 'opacity 0.3s ease';

                            const diff = startX - currentX;
                            if (diff > 100) {
                                this.closeFilterDrawer();
                            } else {
                                filterDrawer.style.transform = 'translateX(0)';
                                filterOverlay.style.opacity = 1;
                            }
                        });
                    }
                }

                openFilterDrawer() {
                    const filterOverlay = document.getElementById(`filterOverlay-${this.componentId}`);
                    const filterDrawer = document.getElementById(`filterDrawer-${this.componentId}`);

                    if (!filterOverlay || !filterDrawer) return;

                    filterOverlay.classList.add('active');
                    filterDrawer.classList.add('active');

                    // Initialize mobile slider
                    setTimeout(() => {
                        if (!this.priceSliders.mobile) {
                            this.priceSliders.mobile = new PriceSlider(this.componentId, 'mobile');
                        }
                    }, 100);
                }

                closeFilterDrawer() {
                    const filterOverlay = document.getElementById(`filterOverlay-${this.componentId}`);
                    const filterDrawer = document.getElementById(`filterDrawer-${this.componentId}`);

                    if (!filterOverlay || !filterDrawer) return;

                    filterOverlay.classList.remove('active');
                    filterDrawer.classList.remove('active');
                }

                showLoading() {
                    const indicator = document.getElementById(`loadingIndicator-${this.componentId}`);
                    const skeleton = document.getElementById(`skeletonLoader-${this.componentId}`);
                    const productsGrid = document.getElementById(`productsGrid-${this.componentId}`);
                    
                    if (indicator) indicator.classList.add('active');
                    if (skeleton) skeleton.style.display = 'grid';
                    if (productsGrid) productsGrid.style.display = 'none';

                    // Clear any existing timeout
                    if (this.loadingTimeout) {
                        clearTimeout(this.loadingTimeout);
                    }

                    // Link to page load instead of fixed timeout
                    this.loadingTimeout = setTimeout(() => {
                        this.hideLoading();
                    }, 5000); // 5 second safety net
                }

                hideLoading() {
                    const indicator = document.getElementById(`loadingIndicator-${this.componentId}`);
                    const skeleton = document.getElementById(`skeletonLoader-${this.componentId}`);
                    const productsGrid = document.getElementById(`productsGrid-${this.componentId}`);
                    
                    if (indicator) indicator.classList.remove('active');
                    if (skeleton) skeleton.style.display = 'none';
                    if (productsGrid) productsGrid.style.display = 'grid';

                    if (this.loadingTimeout) {
                        clearTimeout(this.loadingTimeout);
                        this.loadingTimeout = null;
                    }
                }

                autoHideLoading() {
                    window.addEventListener('load', () => {
                        setTimeout(() => this.hideLoading(), 500);
                    });
                }

                applyMobileFilters() {
                    const form = document.getElementById(`mobileFilterForm-${this.componentId}`);
                    const applyBtn = document.getElementById(`mobileApplyBtn-${this.componentId}`);
                    const drawer = document.getElementById(`filterDrawer-${this.componentId}`);
                    const overlay = document.getElementById(`filterOverlay-${this.componentId}`);

                    if (!form || !applyBtn) return;

                    this.showLoading();
                    applyBtn.classList.add('btn-loading');

                    if (drawer && overlay) {
                        drawer.classList.remove('active');
                        overlay.classList.remove('active');
                    }

                    form.submit();
                }
            }

            // Public API
            return {
                // Initialize all components
                initializeAll() {
                    document.querySelectorAll('[data-component-id]').forEach(container => {
                        const componentId = container.dataset.componentId;
                        const route = componentId; // Use componentId as route identifier
                        
                        if (componentId && !instances.has(componentId)) {
                            const instance = new FilterInstance(componentId, route);
                            instances.set(componentId, instance);
                        }
                    });
                },

                // Helper functions
                showLoading(componentId) {
                    const instance = instances.get(componentId);
                    if (instance) instance.showLoading();
                },

                updatePriceFromInput(type, componentId) {
                    const instance = instances.get(componentId);
                    if (instance && instance.priceSliders[type]) {
                        instance.priceSliders[type].updateFromInput();
                    }
                },

                applyMobileFilters(componentId) {
                    const instance = instances.get(componentId);
                    if (instance) instance.applyMobileFilters();
                },

                removeFilter(filterName, route, componentId) {
                    const instance = instances.get(componentId);
                    if (instance) instance.showLoading();
                    
                    const url = new URL(window.location.href);
                    url.searchParams.delete(filterName);
                    url.searchParams.set('page', '1');
                    window.location.href = url.toString();
                },

                removePriceFilter(route, componentId) {
                    const instance = instances.get(componentId);
                    if (instance) instance.showLoading();
                    
                    const url = new URL(window.location.href);
                    url.searchParams.delete('min_price');
                    url.searchParams.delete('max_price');
                    url.searchParams.set('page', '1');
                    window.location.href = url.toString();
                },

                clearAllFilters(route, componentId) {
                    const instance = instances.get(componentId);
                    if (instance) instance.showLoading();
                    
                    // Simple approach - just remove query parameters
                    const basePath = window.location.pathname.split('?')[0];
                    window.location.href = basePath;
                },

                updateSort(sortValue, route, componentId) {
                    const instance = instances.get(componentId);
                    if (instance) instance.showLoading();
                    
                    const url = new URL(window.location.href);
                    url.searchParams.set('sort', sortValue);
                    url.searchParams.set('page', '1');
                    window.location.href = url.toString();
                }
            };
        })();

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            SearchFilter.initializeAll();
        });

        // Make functions available globally for inline handlers
        function updatePriceFromInput(type, componentId) {
            SearchFilter.updatePriceFromInput(type, componentId);
        }

        function applyMobileFilters(componentId) {
            SearchFilter.applyMobileFilters(componentId);
        }

        function removeFilter(filterName, route, componentId) {
            SearchFilter.removeFilter(filterName, route, componentId);
        }

        function removePriceFilter(route, componentId) {
            SearchFilter.removePriceFilter(route, componentId);
        }

        function clearAllFilters(route, componentId) {
            SearchFilter.clearAllFilters(route, componentId);
        }

        function updateSort(sortValue, route, componentId) {
            SearchFilter.updateSort(sortValue, route, componentId);
        }

        window.SearchFilter = SearchFilter;
    </script>
@endpush