{{-- resources/views/components/search-filter.blade.php --}}
@php
    // Configuration
    $route = $route ?? 'collections';
    $searchPlaceholder = $searchPlaceholder ?? 'Search perfumes...';
    $products = $products ?? collect(); // Products will be passed from parent

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
    </style>
@endpush

<div class="collections-container">
    <!-- Subtle Loading Indicator (Under Navbar) -->
    <div class="loading-indicator" id="loadingIndicator-{{ $route }}"></div>

    <!-- Desktop Sidebar (Hidden on Mobile) -->
    <aside class="filter-sidebar">
        <form method="GET" action="{{ route($route) }}" id="desktopFilterForm-{{ $route }}">
            @if(request('q'))
                <input type="hidden" name="q" value="{{ e(request('q')) }}">
            @endif

            <!-- Price Range Filter -->
            <div class="price-range-group">
                <h3 class="filter-group-title">Price Range</h3>

                <!-- Price Inputs -->
                <div class="price-range-inputs">
                    <div class="price-input-wrapper">
                        <label for="min_price-{{ $route }}">From</label>
                        <input type="number" name="min_price" id="min_price-{{ $route }}" class="price-input"
                            placeholder="Min" value="{{ request('min_price') }}" min="0" step="1"
                            onchange="updatePriceFromInput('desktop', '{{ $route }}')">
                    </div>
                    <div class="price-separator">–</div>
                    <div class="price-input-wrapper">
                        <label for="max_price-{{ $route }}">To</label>
                        <input type="number" name="max_price" id="max_price-{{ $route }}" class="price-input"
                            placeholder="Max" value="{{ request('max_price', 1000) }}" min="0" step="1"
                            onchange="updatePriceFromInput('desktop', '{{ $route }}')">
                    </div>
                </div>

                <!-- Price Range Slider -->
                <div class="price-range-container">
                    <div class="price-range-slider-container">
                        <div class="price-range-slider-track"></div>
                        <div class="price-range-slider-track-fill" id="desktopPriceTrack-{{ $route }}"></div>
                        <div class="price-range-slider-handle" id="desktopMinHandle-{{ $route }}" style="left: 0%;"
                            data-value="0">
                        </div>
                        <div class="price-range-slider-handle" id="desktopMaxHandle-{{ $route }}" style="left: 100%;"
                            data-value="1000"></div>
                    </div>
                    <div class="price-range-labels">
                        <span>$0</span>
                        <span>$1000</span>
                    </div>
                    <div class="price-range-values">
                        <span>$<span id="desktopMinDisplay-{{ $route }}">0</span></span>
                        <span>$<span id="desktopMaxDisplay-{{ $route }}">1000</span></span>
                    </div>
                </div>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button type="submit" class="apply-btn" id="desktopApplyBtn-{{ $route }}" style="flex: 1;">Apply
                    Filters</button>
                <button type="button" class="reset-btn" onclick="clearAllFilters('{{ $route }}')"
                    style="text-align: center; padding: 14px;">Clear All</button>
            </div>
        </form>
    </aside>

    <!-- Main Content Area -->
    <div class="products-area">
        <!-- Search Bar -->
        <div class="search-bar">
            <form method="GET" action="{{ route($route) }}" id="searchForm-{{ $route }}">
                <div class="search-wrapper">
                    <input type="text" name="q" value="{{ e(request('q', '')) }}" placeholder="{{ $searchPlaceholder }}"
                        class="search-input" id="searchInput-{{ $route }}" maxlength="100" aria-label="Search perfumes"
                        autocomplete="off">
                    <button type="button" class="filter-toggle-btn" id="mobileFilterToggle-{{ $route }}"
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
                        <button class="remove-filter-btn" onclick="removeFilter('q', '{{ $route }}')">×</button>
                    </span>
                @endif

                @if(request('min_price') || request('max_price'))
                    @php
                        $minPrice = request('min_price', 0);
                        $maxPrice = request('max_price', '1000+');
                    @endphp
                    <span class="filter-tag">
                        ${{ $minPrice }} - ${{ $maxPrice }}
                        <button class="remove-filter-btn" onclick="removePriceFilter('{{ $route }}')">×</button>
                    </span>
                @endif

                @if(request('sort') && request('sort') != 'newest')
                    <span class="filter-tag">
                        {{ ucfirst(str_replace('_', ' ', e(request('sort')))) }}
                        <button class="remove-filter-btn" onclick="removeFilter('sort', '{{ $route }}')">×</button>
                    </span>
                @endif

                <button onclick="clearAllFilters('{{ $route }}')" class="clear-all-btn">Clear all</button>
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

            <select name="sort" class="sort-select" onchange="updateSort(this.value, '{{ $route }}')">
                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High
                </option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low
                </option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A-Z</option>
            </select>
        </div>

        <!-- Products Grid -->
        @if(isset($products) && $products->count() > 0)
            <div class="products-grid" id="productsGrid-{{ $route }}">
                @foreach($products as $product)
                    <div class="product-card">
                        @if($product->discount_100ml && $product->price_100ml > 0)
                            <div class="sale-badge">
                                SALE
                            </div>
                        @endif

                        <div class="product-image">
                            <img src="{{ $product->main_image ? asset('storage/' . e($product->main_image)) : 'https://images.unsplash.com/photo-1585123334904-89d6f8e5f7b1' }}"
                                alt="{{ e($product->name) }}" loading="lazy">
                        </div>

                        <div class="product-info">
                            <div class="product-brand">{{ e($product->brand->name ?? 'Brand') }}</div>
                            <div class="product-name">{{ e($product->name) }}</div>

                            <div class="product-price">
                                @if($product->discount_100ml)
                                    <span class="current-price">${{ number_format($product->discount_100ml, 2) }}</span>
                                    <span class="original-price">${{ number_format($product->price_100ml, 2) }}</span>
                                @else
                                    <span class="current-price">${{ number_format($product->price_100ml, 2) }}</span>
                                @endif
                            </div>

                            <a href="{{ route('product.show', $product->id) }}" class="view-product-btn">
                                View Product
                            </a>
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
                <div style="font-size: 48px; color: #ddd; margin-bottom: 20px;">👃</div>
                <h3 style="font-size: 18px; margin-bottom: 10px; color: #333;">No perfumes found</h3>
                <p style="color: #666; margin-bottom: 20px;">Try adjusting your search or filters</p>
                <button onclick="clearAllFilters('{{ $route }}')"
                    style="display: inline-block; padding: 12px 24px; background: #000; color: white; text-decoration: none; border-radius: 8px; font-weight: 500; border: none; cursor: pointer;">
                    Clear All Filters
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Mobile Filter Drawer -->
<div class="filter-drawer-overlay" id="filterOverlay-{{ $route }}"></div>
<div class="filter-drawer" id="filterDrawer-{{ $route }}">
    <div class="swipe-indicator"></div>
    <div class="drawer-header">
        <div class="drawer-title">Filter Products</div>
        <button class="close-drawer-btn" id="closeDrawer-{{ $route }}">&times;</button>
    </div>

    <form method="GET" action="{{ route($route) }}" id="mobileFilterForm-{{ $route }}">
        <div class="drawer-content">
            @if(request('q'))
                <input type="hidden" name="q" value="{{ e(request('q')) }}">
            @endif

            <!-- Price Range Filter (Mobile) -->
            <div class="price-range-group">
                <h3 class="filter-group-title">Price Range</h3>

                <div class="price-range-inputs">
                    <div class="price-input-wrapper">
                        <label for="mobile_min_price-{{ $route }}">From</label>
                        <input type="number" name="min_price" id="mobile_min_price-{{ $route }}" class="price-input"
                            placeholder="Min" value="{{ request('min_price') }}" min="0" step="1"
                            onchange="updatePriceFromInput('mobile', '{{ $route }}')">
                    </div>
                    <div class="price-separator">–</div>
                    <div class="price-input-wrapper">
                        <label for="mobile_max_price-{{ $route }}">To</label>
                        <input type="number" name="max_price" id="mobile_max_price-{{ $route }}" class="price-input"
                            placeholder="Max" value="{{ request('max_price', 1000) }}" min="0" step="1"
                            onchange="updatePriceFromInput('mobile', '{{ $route }}')">
                    </div>
                </div>

                <!-- Price Range Slider for Mobile -->
                <div class="price-range-container">
                    <div class="price-range-slider-container">
                        <div class="price-range-slider-track"></div>
                        <div class="price-range-slider-track-fill" id="mobilePriceTrack-{{ $route }}"></div>
                        <div class="price-range-slider-handle" id="mobileMinHandle-{{ $route }}" style="left: 0%;"
                            data-value="0">
                        </div>
                        <div class="price-range-slider-handle" id="mobileMaxHandle-{{ $route }}" style="left: 100%;"
                            data-value="1000"></div>
                    </div>
                    <div class="price-range-labels">
                        <span>$0</span>
                        <span>$1000</span>
                    </div>
                    <div class="price-range-values">
                        <span>$<span id="mobileMinDisplay-{{ $route }}">0</span></span>
                        <span>$<span id="mobileMaxDisplay-{{ $route }}">1000</span></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="drawer-footer">
            <button type="button" class="apply-btn" id="mobileApplyBtn-{{ $route }}"
                onclick="applyMobileFilters('{{ $route }}')">Apply Filters</button>
            <button type="button" class="reset-btn" onclick="clearAllFilters('{{ $route }}')">Reset All</button>
        </div>
    </form>
</div>

@push('scripts')
    <script>
        // ========== SUBTLE LOADING INDICATOR ==========
        let loadingTimeout = {};

        function showLoading(route) {
            const indicator = document.getElementById('loadingIndicator-' + route);
            if (indicator) {
                indicator.classList.add('active');
            }

            // Auto-hide after 3 seconds (safety net)
            loadingTimeout[route] = setTimeout(() => {
                hideLoading(route);
            }, 3000);
        }

        function hideLoading(route) {
            const indicator = document.getElementById('loadingIndicator-' + route);
            if (indicator) {
                indicator.classList.remove('active');
            }
            clearTimeout(loadingTimeout[route]);
        }

        // ========== PRICE RANGE SLIDER FUNCTIONS ==========
        let isDraggingHandle = {};
        let activeHandle = {};
        let maxPrice = 1000;

        function initializePriceSlider(type, route) {
            const minInput = document.getElementById(type === 'desktop' ? 'min_price-' + route : 'mobile_min_price-' + route);
            const maxInput = document.getElementById(type === 'desktop' ? 'max_price-' + route : 'mobile_max_price-' + route);
            const minDisplay = document.getElementById(type === 'desktop' ? 'desktopMinDisplay-' + route : 'mobileMinDisplay-' + route);
            const maxDisplay = document.getElementById(type === 'desktop' ? 'desktopMaxDisplay-' + route : 'mobileMaxDisplay-' + route);
            const priceTrack = document.getElementById(type === 'desktop' ? 'desktopPriceTrack-' + route : 'mobilePriceTrack-' + route);
            const minHandle = document.getElementById(type === 'desktop' ? 'desktopMinHandle-' + route : 'mobileMinHandle-' + route);
            const maxHandle = document.getElementById(type === 'desktop' ? 'desktopMaxHandle-' + route : 'mobileMaxHandle-' + route);

            // Set initial values
            const minValue = parseInt(minInput.value) || 0;
            const maxValue = parseInt(maxInput.value) || 1000;

            // Calculate percentages
            const minPercent = (minValue / maxPrice) * 100;
            const maxPercent = (maxValue / maxPrice) * 100;

            // Update handles and track
            minHandle.style.left = minPercent + '%';
            minHandle.setAttribute('data-value', minValue);
            maxHandle.style.left = maxPercent + '%';
            maxHandle.setAttribute('data-value', maxValue);

            // Update track fill
            priceTrack.style.left = minPercent + '%';
            priceTrack.style.width = (maxPercent - minPercent) + '%';

            // Update displays
            minDisplay.textContent = minValue;
            maxDisplay.textContent = maxValue;

            // Add drag functionality
            [minHandle, maxHandle].forEach(handle => {
                handle.addEventListener('mousedown', function (e) {
                    startDrag(e, route);
                });
                handle.addEventListener('touchstart', function (e) {
                    startDragTouch(e, route);
                });
            });

            function startDrag(e, route) {
                e.preventDefault();
                isDraggingHandle[route] = true;
                activeHandle[route] = e.target;

                document.addEventListener('mousemove', function (e) { drag(e, route); });
                document.addEventListener('mouseup', function () { stopDrag(route); });
            }

            function startDragTouch(e, route) {
                e.preventDefault();
                isDraggingHandle[route] = true;
                activeHandle[route] = e.target;

                document.addEventListener('touchmove', function (e) { dragTouch(e, route); });
                document.addEventListener('touchend', function () { stopDrag(route); });
            }

            function dragTouch(e, route) {
                if (e.touches && e.touches[0]) {
                    drag(e.touches[0], route);
                }
            }

            function drag(e, route) {
                if (!isDraggingHandle[route] || !activeHandle[route]) return;

                const sliderContainer = activeHandle[route].closest('.price-range-slider-container');
                const rect = sliderContainer.getBoundingClientRect();
                let clientX = e.clientX;

                // Calculate position percentage
                let percent = ((clientX - rect.left) / rect.width) * 100;
                percent = Math.max(0, Math.min(100, percent));

                // Calculate value
                const value = Math.round((percent / 100) * maxPrice);

                // Update handle
                if (activeHandle[route].id.includes('Min')) {
                    const maxValue = parseInt(maxHandle.getAttribute('data-value'));
                    if (value > maxValue) {
                        activeHandle[route].style.left = (maxValue / maxPrice) * 100 + '%';
                        activeHandle[route].setAttribute('data-value', maxValue);
                        updatePriceValues(type, route, maxValue, maxValue);
                    } else {
                        activeHandle[route].style.left = percent + '%';
                        activeHandle[route].setAttribute('data-value', value);
                        updatePriceValues(type, route, value, maxValue);
                    }
                } else {
                    const minValue = parseInt(minHandle.getAttribute('data-value'));
                    if (value < minValue) {
                        activeHandle[route].style.left = (minValue / maxPrice) * 100 + '%';
                        activeHandle[route].setAttribute('data-value', minValue);
                        updatePriceValues(type, route, minValue, minValue);
                    } else {
                        activeHandle[route].style.left = percent + '%';
                        activeHandle[route].setAttribute('data-value', value);
                        updatePriceValues(type, route, minValue, value);
                    }
                }

                // Update track
                const minPercent = parseFloat(minHandle.style.left);
                const maxPercent = parseFloat(maxHandle.style.left);
                priceTrack.style.left = minPercent + '%';
                priceTrack.style.width = (maxPercent - minPercent) + '%';
            }

            function stopDrag(route) {
                isDraggingHandle[route] = false;
                activeHandle[route] = null;

                document.removeEventListener('mousemove', function (e) { drag(e, route); });
                document.removeEventListener('mouseup', function () { stopDrag(route); });
                document.removeEventListener('touchmove', function (e) { dragTouch(e, route); });
                document.removeEventListener('touchend', function () { stopDrag(route); });

                // Auto-submit desktop form
                if (type === 'desktop') {
                    setTimeout(() => {
                        submitDesktopFilters(route);
                    }, 300);
                }
            }
        }

        function updatePriceValues(type, route, minValue, maxValue) {
            const minInput = document.getElementById(type === 'desktop' ? 'min_price-' + route : 'mobile_min_price-' + route);
            const maxInput = document.getElementById(type === 'desktop' ? 'max_price-' + route : 'mobile_max_price-' + route);
            const minDisplay = document.getElementById(type === 'desktop' ? 'desktopMinDisplay-' + route : 'mobileMinDisplay-' + route);
            const maxDisplay = document.getElementById(type === 'desktop' ? 'desktopMaxDisplay-' + route : 'mobileMaxDisplay-' + route);

            minInput.value = minValue;
            maxInput.value = maxValue;
            minDisplay.textContent = minValue;
            maxDisplay.textContent = maxValue;
        }

        function updatePriceFromInput(type, route) {
            const minInput = document.getElementById(type === 'desktop' ? 'min_price-' + route : 'mobile_min_price-' + route);
            const maxInput = document.getElementById(type === 'desktop' ? 'max_price-' + route : 'mobile_max_price-' + route);
            const minHandle = document.getElementById(type === 'desktop' ? 'desktopMinHandle-' + route : 'mobileMinHandle-' + route);
            const maxHandle = document.getElementById(type === 'desktop' ? 'desktopMaxHandle-' + route : 'mobileMaxHandle-' + route);
            const priceTrack = document.getElementById(type === 'desktop' ? 'desktopPriceTrack-' + route : 'mobilePriceTrack-' + route);
            const minDisplay = document.getElementById(type === 'desktop' ? 'desktopMinDisplay-' + route : 'mobileMinDisplay-' + route);
            const maxDisplay = document.getElementById(type === 'desktop' ? 'desktopMaxDisplay-' + route : 'mobileMaxDisplay-' + route);

            let minValue = parseInt(minInput.value) || 0;
            let maxValue = parseInt(maxInput.value) || 1000;

            // Ensure min <= max
            if (minValue > maxValue) {
                minValue = maxValue;
                minInput.value = minValue;
            }

            // Ensure values are within range
            minValue = Math.max(0, Math.min(maxPrice, minValue));
            maxValue = Math.max(0, Math.min(maxPrice, maxValue));

            // Calculate percentages
            const minPercent = (minValue / maxPrice) * 100;
            const maxPercent = (maxValue / maxPrice) * 100;

            // Update handles
            minHandle.style.left = minPercent + '%';
            minHandle.setAttribute('data-value', minValue);
            maxHandle.style.left = maxPercent + '%';
            maxHandle.setAttribute('data-value', maxValue);

            // Update track
            priceTrack.style.left = minPercent + '%';
            priceTrack.style.width = (maxPercent - minPercent) + '%';

            // Update displays
            minDisplay.textContent = minValue;
            maxDisplay.textContent = maxValue;

            // Auto-submit desktop form
            if (type === 'desktop') {
                setTimeout(() => {
                    submitDesktopFilters(route);
                }, 500);
            }
        }

        // ========== ENHANCED FILTER SUBMISSION ==========
        function submitDesktopFilters(route) {
            const form = document.getElementById('desktopFilterForm-' + route);
            const applyBtn = document.getElementById('desktopApplyBtn-' + route);

            if (!form || !applyBtn) return;

            // Show subtle loading
            showLoading(route);

            // Add quick loading effect to button
            applyBtn.classList.add('btn-loading');

            // Submit form
            setTimeout(() => {
                form.submit();
            }, 300);
        }

        function applyMobileFilters(route) {
            const form = document.getElementById('mobileFilterForm-' + route);
            const applyBtn = document.getElementById('mobileApplyBtn-' + route);
            const drawer = document.getElementById('filterDrawer-' + route);
            const overlay = document.getElementById('filterOverlay-' + route);

            if (!form || !applyBtn) return;

            // Show subtle loading
            showLoading(route);

            // Add quick loading effect to button
            applyBtn.classList.add('btn-loading');

            // Close drawer
            if (drawer && overlay) {
                drawer.classList.remove('active');
                overlay.classList.remove('active');
            }

            // Submit form
            setTimeout(() => {
                form.submit();
            }, 300);
        }

        // ========== MOBILE FILTER DRAWER ==========
        let startX = {};
        let currentX = {};
        let isDraggingDrawer = {};

        function openFilterDrawer(route) {
            const filterOverlay = document.getElementById('filterOverlay-' + route);
            const filterDrawer = document.getElementById('filterDrawer-' + route);

            if (!filterOverlay || !filterDrawer) return;

            filterOverlay.classList.add('active');
            filterDrawer.classList.add('active');

            // Initialize mobile price slider when drawer opens
            setTimeout(() => {
                initializePriceSlider('mobile', route);
            }, 100);
        }

        function closeFilterDrawer(route) {
            const filterOverlay = document.getElementById('filterOverlay-' + route);
            const filterDrawer = document.getElementById('filterDrawer-' + route);

            if (!filterOverlay || !filterDrawer) return;

            filterOverlay.classList.remove('active');
            filterDrawer.classList.remove('active');
        }

        // ========== FILTER FUNCTIONS ==========
        function removeFilter(filterName, route) {
            showLoading(route);
            const url = new URL(window.location.href);
            url.searchParams.delete(filterName);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }

        function removePriceFilter(route) {
            showLoading(route);
            const url = new URL(window.location.href);
            url.searchParams.delete('min_price');
            url.searchParams.delete('max_price');
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }

        function clearAllFilters(route) {
            showLoading(route);

            // Handle different route patterns
            if (route === 'women' || route === 'men' || route === 'unisex') {
                window.location.href = "/collections/" + route;
            } else if (route === 'collections') {
                window.location.href = "/collections";
            } else {
                window.location.href = "/";
            }
        }

        // ========== SORTING ==========
        function updateSort(sortValue, route) {
            showLoading(route);
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sortValue);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }

        // ========== SEARCH WITH LOADING ==========
        let searchTimeout = {};

        function setupSearchInput(route) {
            const searchInput = document.getElementById('searchInput-' + route);
            if (!searchInput) return;

            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout[route]);

                // Show searching state
                this.classList.add('searching');

                searchTimeout[route] = setTimeout(() => {
                    this.classList.remove('searching');
                    showLoading(route);
                    this.form.submit();
                }, 500);
            });
        }

        // ========== INITIALIZE ==========
        function initializeSearchFilter(route) {
            // Initialize desktop price slider
            initializePriceSlider('desktop', route);

            // Setup search input
            setupSearchInput(route);

            // Setup mobile drawer events
            const mobileFilterToggle = document.getElementById('mobileFilterToggle-' + route);
            const filterOverlay = document.getElementById('filterOverlay-' + route);
            const closeDrawer = document.getElementById('closeDrawer-' + route);
            const filterDrawer = document.getElementById('filterDrawer-' + route);

            if (mobileFilterToggle) {
                mobileFilterToggle.addEventListener('click', function () {
                    openFilterDrawer(route);
                });
            }

            if (filterOverlay) {
                filterOverlay.addEventListener('click', function () {
                    closeFilterDrawer(route);
                });
            }

            if (closeDrawer) {
                closeDrawer.addEventListener('click', function () {
                    closeFilterDrawer(route);
                });
            }

            // Touch gestures for swipe to close
            if (filterDrawer) {
                filterDrawer.addEventListener('touchstart', (e) => {
                    startX[route] = e.touches[0].clientX;
                    currentX[route] = startX[route];
                    isDraggingDrawer[route] = true;
                    filterDrawer.style.transition = 'none';
                });

                filterDrawer.addEventListener('touchmove', (e) => {
                    if (!isDraggingDrawer[route]) return;
                    currentX[route] = e.touches[0].clientX;
                    const diff = startX[route] - currentX[route];
                    if (diff < 0) return;
                    filterDrawer.style.transform = `translateX(${-diff}px)`;
                    filterOverlay.style.opacity = 1 - (diff / 320);
                });

                filterDrawer.addEventListener('touchend', () => {
                    if (!isDraggingDrawer[route]) return;
                    isDraggingDrawer[route] = false;
                    filterDrawer.style.transition = 'transform 0.3s ease';
                    filterOverlay.style.transition = 'opacity 0.3s ease';

                    const diff = startX[route] - currentX[route];
                    const threshold = 100;

                    if (diff > threshold) {
                        closeFilterDrawer(route);
                    } else {
                        filterDrawer.style.transform = 'translateX(0)';
                        filterOverlay.style.opacity = 1;
                    }
                });
            }

            // Auto-hide loading indicator when page loads
            setTimeout(() => hideLoading(route), 1000);
        }

        // Initialize for each route on the page
        // Initialize for each route on the page
        document.addEventListener('DOMContentLoaded', function () {
            // Add ALL possible route variations
            const routes = ['collections', 'women', 'men', 'unisex', 'collections.women', 'collections.men', 'collections.unisex'];

            routes.forEach(route => {
                if (document.getElementById('searchInput-' + route)) {
                    console.log('Initializing search filter for route:', route);
                    initializeSearchFilter(route);
                }
            });

            // Hide loading indicator when images are loaded
            window.addEventListener('load', function () {
                routes.forEach(route => {
                    if (document.getElementById('searchInput-' + route)) {
                        hideLoading(route);
                    }
                });
            });
        });
    </script>
@endpush