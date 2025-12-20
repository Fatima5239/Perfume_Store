@extends('admin.layouts.app')

@section('page-title', 'Products')

@section('content')

<div class="mb-6">
    <!-- Mobile Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Products</h2>
            <p class="text-sm text-gray-600 mt-1">{{ $products->total() }} total products</p>
        </div>
        <a href="{{ route('admin.products.create') }}" 
           class="btn-touch bg-black hover:bg-gray-800 text-white px-5 py-3 rounded-xl font-medium shadow-sm flex items-center justify-center sm:inline-flex">
            <i class="fas fa-plus mr-2"></i>
            <span>Add New Product</span>
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                <p class="text-green-700 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Mobile Search & Filters -->
    <div class="mb-6 space-y-4">
        <div class="relative">
            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="search" 
                   placeholder="Search products by name..." 
                   class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-black focus:border-black text-base"
                   id="searchInput">
        </div>
        
        <div class="flex flex-wrap gap-2">
            <select class="flex-1 min-w-[140px] px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-black" id="availabilityFilter">
                <option value="">All Availability</option>
                <option value="available">Available</option>
                <option value="out_of_stock">Out of Stock</option>
            </select>
            
            <select class="flex-1 min-w-[140px] px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-black" id="genderFilter">
                <option value="">All Genders</option>
                <option value="men">Men</option>
                <option value="women">Women</option>
                <option value="unisex">Unisex</option>
            </select>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <div class="bg-white p-4 rounded-xl border shadow-sm">
            <div class="text-2xl font-bold text-gray-800">{{ $products->total() }}</div>
            <div class="text-sm text-gray-600">Total Products</div>
        </div>
        <div class="bg-white p-4 rounded-xl border shadow-sm">
            <div class="text-2xl font-bold text-green-600">{{ $products->where('available', 1)->count() }}</div>
            <div class="text-sm text-gray-600">Available</div>
        </div>
        <div class="bg-white p-4 rounded-xl border shadow-sm">
            <div class="text-2xl font-bold text-red-600">{{ $products->where('available', 0)->count() }}</div>
            <div class="text-sm text-gray-600">Out of Stock</div>
        </div>
        <div class="bg-white p-4 rounded-xl border shadow-sm">
            <div class="text-2xl font-bold text-purple-600">{{ $products->unique('brand_id')->count() }}</div>
            <div class="text-sm text-gray-600">Brands</div>
        </div>
    </div>

    <!-- Product Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4" id="productsGrid">
        @forelse($products as $product)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
            <!-- Image Section -->
            <div class="relative h-48 bg-gray-100 overflow-hidden">
                @if($product->main_image && Storage::disk('public')->exists($product->main_image))
                    <img src="{{ asset('storage/'.$product->main_image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                        <i class="fas fa-image text-4xl mb-2"></i>
                        <span class="text-sm">No Image</span>
                    </div>
                @endif
                
                <!-- Availability Badge -->
                <div class="absolute top-3 left-3">
                    <span class="px-3 py-1 text-xs font-medium rounded-full 
                        {{ $product->available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $product->available ? 'Available' : 'Out of Stock' }}
                    </span>
                </div>
                
                <!-- Gender Badge -->
                <div class="absolute top-3 right-3">
                    <span class="px-3 py-1 text-xs font-medium rounded-full 
                        {{ $product->gender == 'men' ? 'bg-blue-100 text-blue-800' : 
                           ($product->gender == 'women' ? 'bg-pink-100 text-pink-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ ucfirst($product->gender) }}
                    </span>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-4">
                <!-- Brand -->
                @if($product->brand)
                <div class="mb-2">
                    <span class="text-xs font-medium text-gray-500">{{ $product->brand->name }}</span>
                </div>
                @endif

                <!-- Product Name -->
                <h3 class="font-bold text-gray-900 mb-2 line-clamp-2" title="{{ $product->name }}">
                    {{ $product->name }}
                </h3>

                <!-- Fragrance Types -->
                @if($product->fragrances->isNotEmpty())
                <div class="mb-3">
                    <div class="flex flex-wrap gap-1">
                        @foreach($product->fragrances->take(2) as $fragrance)
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">
                                {{ $fragrance->name }}
                            </span>
                        @endforeach
                        @if($product->fragrances->count() > 2)
                            <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded">
                                +{{ $product->fragrances->count() - 2 }}
                            </span>
                        @endif
                    </div>
                </div>
                @endif

                <!-- 100ml Price Display -->
                <div class="mb-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-500">100ml Price:</span>
                            <div class="flex items-center gap-2 mt-1">
                                @if($product->discount_100ml && $product->discount_100ml > 0)
                                    <span class="text-sm font-bold text-green-700">
                                        ${{ number_format($product->discount_100ml, 2) }}
                                    </span>
                                    <span class="text-xs text-gray-500 line-through">
                                        ${{ number_format($product->price_100ml, 2) }}
                                    </span>
                                    <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-0.5 rounded">
                                        -{{ number_format((($product->price_100ml - $product->discount_100ml) / $product->price_100ml) * 100, 0) }}%
                                    </span>
                                @else
                                    <span class="text-sm font-bold text-gray-900">
                                        ${{ number_format($product->price_100ml, 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Other Size Variations -->
                @if($product->sizes->isNotEmpty())
                <div class="mb-3 pt-2 border-t">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs text-gray-500">Other Sizes:</span>
                        <span class="text-xs text-gray-400">
                            {{ $product->sizes->count() }} sizes
                        </span>
                    </div>
                    <div class="space-y-1.5">
                        @foreach($product->sizes->take(2) as $size)
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-gray-700">{{ $size->size_ml }}ml</span>
                                <div class="flex items-center gap-2">
                                    @if($size->discount_price && $size->discount_price > 0)
                                        <span class="font-medium text-green-700">
                                            ${{ number_format($size->discount_price, 2) }}
                                        </span>
                                        <span class="text-xs text-gray-400 line-through">
                                            ${{ number_format($size->price, 2) }}
                                        </span>
                                    @else
                                        <span class="font-medium text-gray-800">
                                            ${{ number_format($size->price, 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        @if($product->sizes->count() > 2)
                            <div class="text-center pt-1">
                                <span class="text-xs text-gray-500">
                                    +{{ $product->sizes->count() - 2 }} more sizes
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex justify-between items-center pt-3 border-t">
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                           class="btn-touch bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-2 rounded-lg text-sm font-medium flex items-center">
                            <i class="fas fa-edit mr-1.5"></i>
                            <span class="hidden sm:inline">Edit</span>
                        </a>
                        
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn-touch bg-red-50 text-red-600 hover:bg-red-100 px-3 py-2 rounded-lg text-sm font-medium flex items-center"
                                    onclick="return confirm('Are you sure you want to delete this product?')">
                                <i class="fas fa-trash mr-1.5"></i>
                                <span class="hidden sm:inline">Delete</span>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Toggle Availability -->
<form action="{{ route('admin.products.update', $product->id) }}" method="POST" class="inline">
    @csrf
    @method('PATCH')
    <input type="hidden" name="available" value="{{ $product->available ? '0' : '1' }}">
    <button type="submit" 
            class="text-sm font-medium px-4 py-2 rounded-lg flex items-center
                   {{ $product->available ? 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100' : 'bg-green-50 text-green-700 hover:bg-green-100' }}">
        <i class="fas {{ $product->available ? 'fa-eye-slash' : 'fa-eye' }} mr-1.5"></i>
        <span>{{ $product->available ? 'Mark Out of Stock' : 'Mark Available' }}</span>
    </button>
</form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16 bg-white rounded-xl border">
            <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
                <i class="fas fa-box-open text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-700 mb-3">No products found</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">Get started by adding your first product to the store</p>
            <a href="{{ route('admin.products.create') }}" 
               class="inline-flex items-center px-8 py-3.5 bg-black hover:bg-gray-800 text-white rounded-xl font-medium shadow-sm">
                <i class="fas fa-plus mr-3"></i>
                Add New Product
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="mt-8">
        <div class="bg-white p-4 rounded-xl border shadow-sm">
            {{ $products->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Mobile Floating Action Button -->
<div class="fixed bottom-6 right-6 lg:hidden z-40">
    <a href="{{ route('admin.products.create') }}" 
       class="w-14 h-14 bg-black text-white rounded-full shadow-lg flex items-center justify-center hover:bg-gray-800 transition active:scale-95"
       aria-label="Add new product">
        <i class="fas fa-plus text-xl"></i>
    </a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const availabilityFilter = document.getElementById('availabilityFilter');
    const genderFilter = document.getElementById('genderFilter');
    const productsGrid = document.getElementById('productsGrid');
    
    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const availabilityValue = availabilityFilter.value;
        const genderValue = genderFilter.value;
        
        document.querySelectorAll('#productsGrid > div.bg-white').forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const availability = card.querySelector('.absolute.top-3.left-3 span').textContent.toLowerCase();
            const gender = card.querySelector('.absolute.top-3.right-3 span').textContent.toLowerCase();
            
            const matchesSearch = !searchTerm || name.includes(searchTerm);
            const matchesAvailability = !availabilityValue || 
                (availabilityValue === 'available' && availability.includes('available')) ||
                (availabilityValue === 'out_of_stock' && availability.includes('out'));
            const matchesGender = !genderValue || gender.includes(genderValue);
            
            if (matchesSearch && matchesAvailability && matchesGender) {
                card.style.display = 'block';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 10);
            } else {
                card.style.opacity = '0';
                card.style.transform = 'translateY(10px)';
                setTimeout(() => {
                    card.style.display = 'none';
                }, 300);
            }
        });
        
        // Check if any products are visible
        const visibleCards = Array.from(document.querySelectorAll('#productsGrid > div.bg-white')).filter(card => 
            card.style.display !== 'none'
        );
        
        // Show/hide empty state
        let emptyState = document.getElementById('emptyState');
        if (visibleCards.length === 0) {
            if (!emptyState) {
                emptyState = document.createElement('div');
                emptyState.id = 'emptyState';
                emptyState.className = 'col-span-full text-center py-16 bg-white rounded-xl border';
                emptyState.innerHTML = `
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-search text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-3">No matching products found</h3>
                    <p class="text-gray-500 mb-4">Try adjusting your search or filters</p>
                    <button onclick="resetFilters()" 
                            class="inline-flex items-center px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium">
                        Clear Filters
                    </button>
                `;
                productsGrid.appendChild(emptyState);
            }
        } else if (emptyState) {
            emptyState.remove();
        }
    }
    
    function resetFilters() {
        searchInput.value = '';
        availabilityFilter.value = '';
        genderFilter.value = '';
        filterProducts();
    }
    
    searchInput.addEventListener('input', filterProducts);
    availabilityFilter.addEventListener('change', filterProducts);
    genderFilter.addEventListener('change', filterProducts);
    
    // Add smooth transitions to cards
    document.querySelectorAll('#productsGrid > div.bg-white').forEach(card => {
        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    });
    
    // Prevent form submission on Enter in search
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });
    
    // Mobile touch optimization
    document.querySelectorAll('.btn-touch').forEach(button => {
        button.addEventListener('touchstart', function() {
            this.classList.add('active:scale-95');
        });
        button.addEventListener('touchend', function() {
            this.classList.remove('active:scale-95');
        });
    });
});
</script>

<style>
    @media (max-width: 640px) {
        .btn-touch { 
            min-height: 44px !important; 
            min-width: 44px !important; 
            display: inline-flex !important; 
            align-items: center !important; 
            justify-content: center !important; 
        }
        .grid { gap: 12px !important; }
        .grid > div { 
            margin-bottom: 0 !important;
            border-radius: 16px !important;
        }
        .p-4 {
            padding: 16px !important;
        }
        h3 {
            font-size: 16px !important;
            line-height: 1.4 !important;
        }
        .text-sm {
            font-size: 14px !important;
        }
        .text-xs {
            font-size: 12px !important;
        }
        .relative.h-48 {
            height: 192px !important;
        }
    }
    
    @media (min-width: 641px) and (max-width: 768px) {
        .grid { 
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    
    @media (min-width: 769px) and (max-width: 1024px) {
        .grid { 
            grid-template-columns: repeat(3, 1fr) !important;
        }
    }
    
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        word-break: break-word;
    }
    
    .hover\:scale-105:hover {
        transform: scale(1.05);
    }
    
    .transition-transform {
        transition: transform 0.3s ease;
    }
    
    .transition-shadow {
        transition: box-shadow 0.3s ease;
    }
    
    .active\:scale-95:active {
        transform: scale(0.95);
    }
    
    /* Pagination mobile styles */
    @media (max-width: 640px) {
        .pagination { 
            display: flex !important;
            flex-wrap: wrap !important; 
            justify-content: center !important; 
            gap: 4px !important;
        }
        .pagination li { 
            margin: 0 !important; 
        }
        .pagination .page-link { 
            padding: 12px 16px !important; 
            min-width: 44px !important; 
            min-height: 44px !important;
            text-align: center !important; 
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 12px !important;
            font-size: 14px !important;
        }
        .pagination .disabled .page-link,
        .pagination .active .page-link {
            min-width: 44px !important;
            min-height: 44px !important;
        }
    }
    
    /* Focus styles for accessibility */
    input:focus,
    select:focus,
    button:focus,
    a:focus {
        outline: 2px solid #000;
        outline-offset: 2px;
    }
    
    /* Reduce motion for accessibility */
    @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }
</style>
@endsection