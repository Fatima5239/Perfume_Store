<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Fragrance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use App\Services\TrackingService; // ADD THIS LINE

class ShopController extends Controller
{
    /**
     * Display all collections with filters
     */
    public function allCollections(Request $request)
    {
        try {
            // INPUT VALIDATION
            $validator = Validator::make($request->all(), [
                'q' => ['nullable', 'string', 'max:100'],
                'brand' => ['nullable', 'integer', 'exists:brands,id'],
                'gender' => ['nullable', 'in:men,women,unisex'],
                'price_range' => ['nullable', 'in:under50,50-100,100-200,over200'],
                'min_price' => ['nullable', 'numeric', 'min:0'],
                'max_price' => ['nullable', 'numeric', 'min:0'],
                'sort' => ['nullable', 'in:relevance,price_low,price_high,newest,name'],
            ], [
                'q.max' => 'Search term is too long.',
                'brand.exists' => 'Selected brand does not exist.',
                'gender.in' => 'Invalid gender selection.',
                'price_range.in' => 'Invalid price range.',
                'min_price.min' => 'Minimum price must be at least 0.',
                'max_price.min' => 'Maximum price must be at least 0.',
            ]);

            if ($validator->fails()) {
                \Log::warning('Invalid collections input:', [
                    'input' => $request->all(),
                    'errors' => $validator->errors()
                ]);
                
                $request->replace($request->only(['page']));
            }

            // Start with base query
            $query = Product::where('status', 'active')
                ->where('available', true)
                ->with(['brand', 'sizes']);
            
            // SEARCH FUNCTIONALITY
            if ($request->filled('q')) {
                $searchTerm = $this->sanitizeSearchQuery($request->q);
                
                if (strlen($searchTerm) >= 2) {
                    $cleanQuery = $this->escapeLikeQuery($searchTerm);
                    $query->where(function($q) use ($cleanQuery) {
                        $q->where('name', 'LIKE', $cleanQuery)
                          ->orWhere('description', 'LIKE', $cleanQuery)
                          ->orWhere('fragrance_notes', 'LIKE', $cleanQuery)
                          ->orWhereHas('brand', function($q) use ($cleanQuery) {
                              $q->where('name', 'LIKE', $cleanQuery);
                          })
                          ->orWhereHas('fragrances', function($q) use ($cleanQuery) {
                              $q->where('name', 'LIKE', $cleanQuery);
                          });
                    });
                }
            }
            
            // Secure gender filter
            if ($request->filled('gender') && in_array($request->gender, ['men', 'women', 'unisex'])) {
                $query->where('gender', $request->gender);
            }
            
            // Secure brand filter
            if ($request->filled('brand') && is_numeric($request->brand)) {
                $query->where('brand_id', (int)$request->brand);
            }
            
            // PRICE RANGE FILTER
            if ($request->filled('price_range') && in_array($request->price_range, ['under50', '50-100', '100-200', 'over200'])) {
                switch ($request->price_range) {
                    case 'under50':
                        $query->where('price_100ml', '<', 50);
                        break;
                    case '50-100':
                        $query->whereBetween('price_100ml', [50, 100]);
                        break;
                    case '100-200':
                        $query->whereBetween('price_100ml', [100, 200]);
                        break;
                    case 'over200':
                        $query->where('price_100ml', '>', 200);
                        break;
                }
            } else {
                // Original price filters
                if ($request->filled('min_price') && $request->filled('max_price')) {
                    $minPrice = (float)$request->min_price;
                    $maxPrice = (float)$request->max_price;
                    
                    if ($minPrice <= $maxPrice) {
                        $query->whereBetween('price_100ml', [$minPrice, $maxPrice]);
                    } else {
                        $query->whereBetween('price_100ml', [$maxPrice, $minPrice]);
                    }
                } else {
                    if ($request->filled('min_price')) {
                        $query->where('price_100ml', '>=', (float)$request->min_price);
                    }
                    
                    if ($request->filled('max_price')) {
                        $query->where('price_100ml', '<=', (float)$request->max_price);
                    }
                }
            }
            
            // Enhanced sorting options
            $sort = $request->get('sort', 'newest');
            $allowedSorts = ['newest', 'price_low', 'price_high', 'name', 'relevance'];
            
            if (in_array($sort, $allowedSorts)) {
                switch ($sort) {
                    case 'price_low':
                        $query->orderBy('price_100ml', 'asc');
                        break;
                    case 'price_high':
                        $query->orderBy('price_100ml', 'desc');
                        break;
                    case 'name':
                        $query->orderBy('name', 'asc');
                        break;
                    case 'relevance':
                        if ($request->filled('q') && strlen($request->q) >= 2) {
                            $cleanQuery = $this->escapeLikeQuery($this->sanitizeSearchQuery($request->q));
                            $cleanQueryStart = $this->escapeLikeQuery($this->sanitizeSearchQuery($request->q), true);
                            $query->orderByRaw("
                                CASE 
                                    WHEN name LIKE ? THEN 1
                                    WHEN fragrance_notes LIKE ? THEN 2
                                    WHEN description LIKE ? THEN 3
                                    ELSE 4
                                END
                            ", [$cleanQueryStart, $cleanQuery, $cleanQuery]);
                        } else {
                            $query->orderBy('created_at', 'desc');
                        }
                        break;
                    default: // newest
                        $query->orderBy('created_at', 'desc');
                }
            }
            
            $products = $query->paginate(12)->withQueryString();
            $brands = Brand::where('status', 'active')->get(['id', 'name']);
            
            // ========== ADD TRACKING HERE ==========
            // Track search if there's a search query
            if ($request->filled('q')) {
                TrackingService::trackSearch($request, $products);
            }
            // Track page view
            TrackingService::trackPageView($request, 'collection');
            // ========== END TRACKING ==========
            
            return view('shop.collections', [
                'products' => $products,
                'brands' => $brands,
                'filters' => $request->only(['gender', 'brand', 'min_price', 'max_price', 'sort', 'q', 'price_range'])
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Collections error: ' . $e->getMessage());
            return view('shop.error')->with('message', 'Unable to load collections.');
        }
    }

   /**
     * Helper method for gender collections
     */
    private function showGenderCollection($gender, $title, Request $request = null)
    {
        try {
            if (!in_array($gender, ['men', 'women', 'unisex'])) {
                abort(404);
            }
        
            // Start with base query
            $query = Product::where('status', 'active')
                ->where('available', true)
                ->where('gender', $gender)
                ->with(['brand', 'sizes']);
            
            // SEARCH FUNCTIONALITY
            if ($request && $request->filled('q')) {
                $searchTerm = $this->sanitizeSearchQuery($request->q);
                
                if (strlen($searchTerm) >= 2) {
                    $cleanQuery = $this->escapeLikeQuery($searchTerm);
                    $query->where(function($q) use ($cleanQuery) {
                        $q->where('name', 'LIKE', $cleanQuery)
                          ->orWhere('description', 'LIKE', $cleanQuery)
                          ->orWhere('fragrance_notes', 'LIKE', $cleanQuery)
                          ->orWhereHas('brand', function($q) use ($cleanQuery) {
                              $q->where('name', 'LIKE', $cleanQuery);
                          });
                    });
                }
            }
            
            // PRICE FILTERS
            if ($request && $request->filled('min_price') && $request->filled('max_price')) {
                $minPrice = (float)$request->min_price;
                $maxPrice = (float)$request->max_price;
                
                if ($minPrice <= $maxPrice) {
                    $query->whereBetween('price_100ml', [$minPrice, $maxPrice]);
                } else {
                    $query->whereBetween('price_100ml', [$maxPrice, $minPrice]);
                }
            } else {
                if ($request && $request->filled('min_price')) {
                    $query->where('price_100ml', '>=', (float)$request->min_price);
                }
                
                if ($request && $request->filled('max_price')) {
                    $query->where('price_100ml', '<=', (float)$request->max_price);
                }
            }
            
            // SORTING
            $sort = $request ? $request->get('sort', 'newest') : 'newest';
            switch ($sort) {
                case 'price_low':
                    $query->orderBy('price_100ml', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price_100ml', 'desc');
                    break;
                case 'name':
                    $query->orderBy('name', 'asc');
                    break;
                default: // newest
                    $query->orderBy('created_at', 'desc');
            }
            
            $products = $query->paginate(12)->withQueryString();
            
            // ========== ADD TRACKING HERE ==========
            // Track search if there's a search query
            if ($request && $request->filled('q')) {
                TrackingService::trackSearch($request, $products);
            }
            // Track page view for gender collection
            if ($request) {
                TrackingService::trackPageView($request, "collection_{$gender}");
            }
            // ========== END TRACKING ==========
            
            return view('shop.category', [
                'products' => $products,
                'title' => $title,
                'gender' => $gender,
                'searchQuery' => $request ? $request->q : null,
                'minPrice' => $request ? $request->min_price : null,
                'maxPrice' => $request ? $request->max_price : null,
                'sort' => $sort
            ]);
            
        } catch (\Exception $e) {
            \Log::error("{$gender} collection error: " . $e->getMessage());
            return view('shop.error')->with('message', 'Unable to load collection.');
        }
    }

    /**
     * Display women's collection
     */
    public function women(Request $request)
    {
        return $this->showGenderCollection('women', "Women's Collection", $request);
    }

    /**
     * Display men's collection
     */
    public function men(Request $request)
    {
        return $this->showGenderCollection('men', "Men's Collection", $request);
    }

    /**
     * Display unisex collection
     */
    public function unisex(Request $request)
    {
        return $this->showGenderCollection('unisex', "Unisex Collection", $request);
    }
    
    /**
     * Display single product
     */
    public function show($id)
    {
        try {
            if (!is_numeric($id)) {
                abort(404);
            }
            
            $product = Product::where('status', 'active')
                ->where('available', true)
                ->with(['brand', 'sizes', 'fragrances'])
                ->findOrFail($id);
            
            // RELATED PRODUCTS
            $priceRange = $product->price_100ml * 0.3;
            $minPrice = max(0, $product->price_100ml - $priceRange);
            $maxPrice = $product->price_100ml + $priceRange;
            
            $relatedProducts = Product::where('status', 'active')
                ->where('available', true)
                ->where('gender', $product->gender)
                ->whereBetween('price_100ml', [$minPrice, $maxPrice])
                ->where('id', '!=', $product->id)
                ->with(['brand', 'sizes'])
                ->inRandomOrder()
                ->take(4)
                ->get();
            
            // Fallback
            if ($relatedProducts->count() < 4) {
                $needed = 4 - $relatedProducts->count();
                
                $fallbackProducts = Product::where('status', 'active')
                    ->where('available', true)
                    ->where('gender', $product->gender)
                    ->where('id', '!=', $product->id)
                    ->whereNotIn('id', $relatedProducts->pluck('id'))
                    ->with(['brand', 'sizes'])
                    ->inRandomOrder()
                    ->take($needed)
                    ->get();
                
                $relatedProducts = $relatedProducts->merge($fallbackProducts);
            }
            
            // ========== ADD TRACKING HERE ==========
            TrackingService::trackProductView($product->id);
            TrackingService::trackPageView(request(), 'product');
            // ========== END TRACKING ==========
            
            return view('shop.show', [
                'product' => $product,
                'relatedProducts' => $relatedProducts
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404);
        } catch (\Exception $e) {
            \Log::error("Product show error (ID: {$id}): " . $e->getMessage());
            return view('shop.error')->with('message', 'Unable to load product.');
        }
    }
    
    /**
     * AJAX search suggestions with XSS protection
     */
    public function searchSuggestions(Request $request)
    {
        // Rate limiting check
        $key = 'suggestions:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 30)) {
            return response()->json(['error' => 'Too many requests'], 429);
        }
        
        RateLimiter::hit($key, 60);
        
        $validator = Validator::make($request->all(), [
            'q' => ['required', 'string', 'min:2', 'max:50'],
        ]);
        
        if ($validator->fails()) {
            return response()->json([]);
        }
        
        $query = $this->sanitizeSearchQuery($request->q);
        
        // Product suggestions
        $products = Product::where('status', 'active')
            ->where('available', true)
            ->where(function($q) use ($query) {
                $cleanQuery = $this->escapeLikeQuery($query);
                $q->where('name', 'LIKE', $cleanQuery)
                  ->orWhereHas('brand', function($q) use ($cleanQuery) {
                      $q->where('name', 'LIKE', $cleanQuery);
                  });
            })
            ->with('brand')
            ->limit(5)
            ->get(['id', 'name', 'main_image', 'price_100ml', 'discount_100ml', 'brand_id'])
            ->map(function($product) {
                return [
                    'type' => 'product',
                    'id' => (int)$product->id,
                    'name' => e($product->name),
                    'brand' => e($product->brand->name ?? ''),
                    'price' => number_format($product->discount_100ml ?? $product->price_100ml, 2),
                    'image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
                    'url' => route('product.show', $product->id)
                ];
            });
        
        // Brand suggestions
        $brands = Brand::where('status', 'active')
            ->where('name', 'LIKE', $this->escapeLikeQuery($query))
            ->limit(3)
            ->get(['id', 'name'])
            ->map(function($brand) {
                return [
                    'type' => 'brand',
                    'id' => (int)$brand->id,
                    'name' => e($brand->name),
                    'url' => route('collections', ['brand' => $brand->id])
                ];
            });
        
        // Combine suggestions
        $suggestions = collect()
            ->merge($products)
            ->merge($brands)
            ->take(8);
        
        return response()->json($suggestions);
    }
    
    /**
     * Sanitize search query
     */
    private function sanitizeSearchQuery(string $query): string
    {
        // Remove HTML tags
        $query = strip_tags($query);
        
        // Convert special characters to HTML entities
        $query = htmlspecialchars($query, ENT_QUOTES | ENT_HTML5, 'UTF-8', false);
        
        // Trim and limit length
        $query = trim($query);
        $query = substr($query, 0, 100);
        
        // Remove excessive whitespace
        $query = preg_replace('/\s+/', ' ', $query);
        
        return $query;
    }
    
    /**
     * Escape LIKE query for SQL
     */
    private function escapeLikeQuery(string $query, bool $startWith = false): string
    {
        // Escape SQL wildcards
        $query = str_replace(['%', '_', '\\'], ['\%', '\_', '\\\\'], $query);
        
        return $startWith ? $query . '%' : '%' . $query . '%';
    }
    
    /**
     * Safe fallback for invalid search
     */
    private function safeSearchFallback()
    {
        $products = Product::where('status', 'active')
            ->where('available', true)
            ->with(['brand', 'sizes'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        
        $brands = Brand::where('status', 'active')->get(['id', 'name']);
        
        return view('shop.collections', [
            'products' => $products,
            'query' => '',
            'brands' => $brands,
            'popularFragrances' => collect(),
            'selectedBrand' => null,
            'selectedGender' => null,
            'selectedPriceRange' => null,
            'selectedSort' => 'newest',
            'totalResults' => $products->total(),
            'error' => 'Invalid search parameters. Showing all products.'
        ]);
    }
}