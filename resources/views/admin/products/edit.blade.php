@extends('admin.layouts.app')

@section('page-title', 'Edit Product')

@section('content')

<div class="min-h-screen bg-gray-50">
    <!-- Mobile Header -->
    <div class="sticky top-0 z-50 bg-white border-b border-gray-200 px-4 py-3 shadow-sm">
        <div class="flex items-center justify-between">
            <button type="button" onclick="history.back()" 
                    class="w-10 h-10 flex items-center justify-center rounded-lg bg-gray-100">
                <i class="fas fa-chevron-left text-gray-700"></i>
            </button>
            
            <div>
                <h1 class="text-lg font-semibold text-gray-900">Edit Product</h1>
                <p class="text-xs text-gray-500">ID: #{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            
            <button type="submit" form="productForm"
                    class="px-4 py-2 bg-black text-white rounded-lg text-sm font-medium">
                Update
            </button>
        </div>
        
        <!-- Progress Steps (Mobile) -->
        <div class="mt-4">
            <div class="flex items-center justify-center space-x-2">
                <div class="h-1.5 w-12 bg-black rounded-full"></div>
                <div class="h-1.5 w-12 bg-black rounded-full"></div>
                <div class="h-1.5 w-12 bg-black rounded-full"></div>
                <div class="h-1.5 w-12 bg-black rounded-full"></div>
                <div class="h-1.5 w-12 bg-black rounded-full"></div>
            </div>
        </div>
    </div>

    <!-- Mobile Form -->
    <form id="productForm" action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="pb-24">
        @csrf
        @method('PUT')
        
        <!-- Error Display -->
        @if($errors->any())
        <div class="mx-4 my-4">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">
                            Please fix the following errors:
                        </p>
                        <ul class="list-disc list-inside text-sm text-red-700 mt-1 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- SECTION 1: Basic Info -->
        <div class="mb-6">
            <div class="bg-white px-4 py-3 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center mr-3 text-sm">
                        1
                    </div>
                    <h2 class="text-base font-semibold text-gray-900">Basic Information</h2>
                </div>
            </div>
            
            <div class="bg-white px-4 py-4 space-y-5">
                <!-- Product Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1.5">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $product->name) }}" 
                           class="w-full px-4 py-3.5 bg-white border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-xl text-base focus:border-black focus:ring-1 focus:ring-black"
                           placeholder="Enter product name"
                           required
                           autocomplete="off">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1.5">
                        Description
                    </label>
                    <textarea name="description" 
                              rows="3"
                              class="w-full px-4 py-3.5 bg-white border {{ $errors->has('description') ? 'border-red-500' : 'border-gray-300' }} rounded-xl text-base focus:border-black focus:ring-1 focus:ring-black"
                              placeholder="Enter product description (optional)"
                              autocomplete="off">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fragrance Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1.5">
                        Fragrance Notes
                    </label>
                    <textarea name="fragrance_notes" 
                              rows="2"
                              class="w-full px-4 py-3.5 bg-white border {{ $errors->has('fragrance_notes') ? 'border-red-500' : 'border-gray-300' }} rounded-xl text-base focus:border-black focus:ring-1 focus:ring-black"
                              placeholder="e.g., Top: Bergamot, Middle: Jasmine, Base: Sandalwood"
                              autocomplete="off">{{ old('fragrance_notes', $product->fragrance_notes) }}</textarea>
                    @error('fragrance_notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Brand -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1.5">Brand</label>
                    <select name="brand_id" 
                            class="w-full px-4 py-3.5 bg-white border {{ $errors->has('brand_id') ? 'border-red-500' : 'border-gray-300' }} rounded-xl text-base appearance-none focus:border-black focus:ring-1 focus:ring-black">
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1.5">
                        Gender <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(['men', 'women', 'unisex'] as $gender)
                            <label class="block">
                                <input type="radio" 
                                       name="gender" 
                                       value="{{ $gender }}" 
                                       class="sr-only peer"
                                       {{ old('gender', $product->gender) == $gender ? 'checked' : '' }}
                                       required>
                                <div class="w-full py-3.5 border {{ $errors->has('gender') ? 'border-red-500' : 'border-gray-300' }} rounded-xl text-center text-sm font-medium
                                            peer-checked:border-black peer-checked:bg-black peer-checked:text-white
                                            active:scale-[0.98] transition">
                                    {{ ucfirst($gender) }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Available Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1.5">
                        Product Availability
                    </label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="block">
                            <input type="radio" 
                                   name="available" 
                                   value="1" 
                                   class="sr-only peer"
                                   {{ old('available', $product->available) == '1' ? 'checked' : '' }}>
                            <div class="w-full py-3.5 border {{ $errors->has('available') ? 'border-red-500' : 'border-gray-300' }} rounded-xl text-center text-sm font-medium
                                        peer-checked:border-green-600 peer-checked:bg-green-50 peer-checked:text-green-700
                                        active:scale-[0.98] transition">
                                <i class="fas fa-check-circle mr-2"></i> Available
                            </div>
                        </label>
                        <label class="block">
                            <input type="radio" 
                                   name="available" 
                                   value="0" 
                                   class="sr-only peer"
                                   {{ old('available', $product->available) == '0' ? 'checked' : '' }}>
                            <div class="w-full py-3.5 border {{ $errors->has('available') ? 'border-red-500' : 'border-gray-300' }} rounded-xl text-center text-sm font-medium
                                        peer-checked:border-red-600 peer-checked:bg-red-50 peer-checked:text-red-700
                                        active:scale-[0.98] transition">
                                <i class="fas fa-times-circle mr-2"></i> Out of Stock
                            </div>
                        </label>
                    </div>
                    @error('available')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- SECTION 2: Standard 100ml Pricing -->
        <div class="mb-6">
            <div class="bg-white px-4 py-3 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center mr-3 text-sm">
                        2
                    </div>
                    <h2 class="text-base font-semibold text-gray-900">Standard 100ml Pricing</h2>
                </div>
            </div>
            
            <div class="bg-white px-4 py-4 space-y-5">
                <!-- 100ml Regular Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1.5">
                        100ml Regular Price <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-lg">$</span>
                        <input type="number" 
                               step="0.01" 
                               name="price_100ml" 
                               value="{{ old('price_100ml', $product->price_100ml) }}" 
                               class="w-full px-4 py-3.5 bg-white border {{ $errors->has('price_100ml') ? 'border-red-500' : 'border-gray-300' }} rounded-xl text-base pl-12 focus:border-black focus:ring-1 focus:ring-black"
                               placeholder="0.00"
                               min="0.01"
                               required
                               inputmode="decimal">
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">
                            100ml
                        </div>
                    </div>
                    @error('price_100ml')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- 100ml Discount Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-1.5">
                        100ml Discount Price
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-lg">$</span>
                        <input type="number" 
                               step="0.01" 
                               name="discount_100ml" 
                               value="{{ old('discount_100ml', $product->discount_100ml) }}" 
                               class="w-full px-4 py-3.5 bg-white border {{ $errors->has('discount_100ml') ? 'border-red-500' : 'border-gray-300' }} rounded-xl text-base pl-12 focus:border-black focus:ring-1 focus:ring-black"
                               placeholder="0.00"
                               min="0"
                               inputmode="decimal">
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">
                            100ml
                        </div>
                    </div>
                    @error('discount_100ml')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price Preview -->
                @php
                    $price_100ml = old('price_100ml', $product->price_100ml);
                    $discount_100ml = old('discount_100ml', $product->discount_100ml);
                @endphp
                
                @if($price_100ml || $discount_100ml)
                <div class="p-4 bg-gray-50 rounded-xl">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">100ml Original:</span>
                            <span class="text-gray-900 font-medium">${{ number_format($price_100ml, 2) }}</span>
                        </div>
                        @if($discount_100ml && $discount_100ml > 0)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">100ml Discount:</span>
                            <span class="text-green-600 font-bold">${{ number_format($discount_100ml, 2) }}</span>
                        </div>
                        <div class="pt-2 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600">You Save:</span>
                                <span class="text-red-600 font-bold">
                                    ${{ number_format($price_100ml - $discount_100ml, 2) }}
                                    @if($price_100ml > 0)
                                    <span class="text-sm">({{ number_format((($price_100ml - $discount_100ml) / $price_100ml) * 100, 0) }}%)</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- SECTION 3: Fragrances -->
        <div class="mb-6">
            <div class="bg-white px-4 py-3 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center mr-3 text-sm">
                        3
                    </div>
                    <h2 class="text-base font-semibold text-gray-900">Fragrances</h2>
                </div>
            </div>
            
            <div class="bg-white px-4 py-4">
                <label class="block text-sm font-medium text-gray-900 mb-3">
                    Select Fragrances
                </label>
                
                <!-- Mobile-friendly multi-select -->
                <div class="space-y-2">
                    @foreach($fragrances as $fragrance)
                    <label class="flex items-center p-3 border border-gray-300 rounded-xl active:bg-gray-50">
                        <input type="checkbox" 
                               name="fragrances[]" 
                               value="{{ $fragrance->id }}" 
                               class="h-5 w-5 text-black rounded border-gray-300 focus:ring-black"
                               {{ in_array($fragrance->id, old('fragrances', $product->fragrances->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <span class="ml-3 text-gray-900">{{ $fragrance->name }}</span>
                    </label>
                    @endforeach
                </div>
                @error('fragrances')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('fragrances.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                <p class="mt-3 text-sm text-gray-500 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Tap to select multiple fragrances
                </p>
            </div>
        </div>

        <!-- SECTION 4: Product Image -->
        <div class="mb-6">
            <div class="bg-white px-4 py-3 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center mr-3 text-sm">
                        4
                    </div>
                    <h2 class="text-base font-semibold text-gray-900">Product Image</h2>
                </div>
            </div>
            
            <div class="bg-white px-4 py-4">
                <!-- Current Image Preview -->
                <div class="mb-4">
                    <p class="text-sm font-medium text-gray-700 mb-2">Current Image</p>
                    @if($product->main_image && Storage::disk('public')->exists($product->main_image))
                        <div class="relative w-32 h-32 mx-auto bg-gray-100 rounded-lg overflow-hidden">
                            <img src="{{ asset('storage/'.$product->main_image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-contain">
                        </div>
                        <p class="text-xs text-gray-500 text-center mt-2">Current product image</p>
                    @else
                        <div class="w-32 h-32 mx-auto bg-gray-100 rounded-lg flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-image text-2xl mb-2"></i>
                            <span class="text-xs">No Image</span>
                        </div>
                    @endif
                </div>
                
                <p class="text-sm text-gray-600 mb-4">Update product image (optional)</p>
                
                <!-- Image Upload Area -->
                <div id="imageUploadArea" 
                     class="border-2 border-dashed {{ $errors->has('main_image') ? 'border-red-500' : 'border-gray-300' }} rounded-xl p-6 text-center active:border-black transition">
                    <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-camera text-xl text-gray-400"></i>
                    </div>
                    <h4 class="font-medium text-gray-900 mb-1">Tap to Upload New Image</h4>
                    <p class="text-sm text-gray-500 mb-4">PNG, JPG up to 5MB</p>
                    <input type="file" 
                           name="main_image" 
                           id="main_image" 
                           class="hidden"
                           accept="image/jpeg,image/jpg,image/png,image/gif">
                    <button type="button" 
                            id="uploadImageBtn"
                            class="px-5 py-2.5 bg-gray-900 text-white rounded-lg text-sm font-medium active:scale-95 transition">
                        Choose from Gallery
                    </button>
                    <p class="text-xs text-gray-400 mt-3">Leave empty to keep current image</p>
                </div>
                @error('main_image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                <!-- New Image Preview -->
                <div id="imagePreview" class="mt-4 hidden">
                    <div class="p-4 border border-gray-200 rounded-xl">
                        <div class="flex items-center">
                            <div class="w-20 h-20 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                <img id="previewImage" class="w-full h-full object-cover" alt="New product image">
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="font-medium text-gray-900 text-sm" id="fileName"></div>
                                <div id="fileSize" class="text-xs text-gray-500 mt-1"></div>
                                <button type="button" 
                                        onclick="removeImage()" 
                                        class="mt-2 text-sm text-red-600 flex items-center active:text-red-800">
                                    <i class="fas fa-trash mr-1.5"></i>
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 5: Other Size Variations -->
        <div class="mb-6">
            <div class="bg-white px-4 py-3 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center mr-3 text-sm">
                            5
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-gray-900">Other Size Variations</h2>
                            <p class="text-xs text-gray-500">Optional - Manage other sizes (50ml, 200ml, etc.)</p>
                        </div>
                    </div>
                    <button type="button" 
                            id="add-size" 
                            class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center active:scale-95"
                            aria-label="Add new size">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            
            <div class="bg-white px-4 py-4">
                <div id="sizes-container" class="space-y-4">
                    @foreach($product->sizes as $index => $size)
                    <div class="size-item p-4 border border-gray-200 rounded-xl bg-gray-50">
                        <div class="flex justify-between items-start mb-4">
                            <h4 class="text-sm font-medium text-gray-900">Additional Size</h4>
                            <button type="button" 
                                    class="w-8 h-8 flex items-center justify-center text-red-500 active:text-red-700 remove-size"
                                    aria-label="Remove size">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <input type="hidden" name="sizes[{{ $index }}][id]" value="{{ $size->id }}">
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1.5">Size (ml) <span class="text-red-500">*</span></label>
                                <input type="number" 
                                       name="sizes[{{ $index }}][size_ml]" 
                                       value="{{ old('sizes.'.$index.'.size_ml', $size->size_ml) }}" 
                                       class="w-full px-4 py-3 bg-white border {{ $errors->has('sizes.'.$index.'.size_ml') ? 'border-red-500' : 'border-gray-300' }} rounded-lg text-base focus:border-black size-ml-input"
                                       placeholder="50"
                                       min="1"
                                       required
                                       inputmode="numeric">
                            </div>
                            
                            <div>
                                <label class="block text-xs text-gray-600 mb-1.5">Regular Price ($) <span class="text-red-500">*</span></label>
                                <input type="number" 
                                       step="0.01"
                                       name="sizes[{{ $index }}][price]" 
                                       value="{{ old('sizes.'.$index.'.price', $size->price) }}" 
                                       class="w-full px-4 py-3 bg-white border {{ $errors->has('sizes.'.$index.'.price') ? 'border-red-500' : 'border-gray-300' }} rounded-lg text-base focus:border-black size-price-input"
                                       placeholder="0.00"
                                       min="0.01"
                                       required
                                       inputmode="decimal">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-600 mb-1.5">Discount Price ($)</label>
                                <input type="number" 
                                       step="0.01"
                                       name="sizes[{{ $index }}][discount_price]" 
                                       value="{{ old('sizes.'.$index.'.discount_price', $size->discount_price) }}" 
                                       class="w-full px-4 py-3 bg-white border {{ $errors->has('sizes.'.$index.'.discount_price') ? 'border-red-500' : 'border-gray-300' }} rounded-lg text-base focus:border-black size-discount-input"
                                       placeholder="0.00"
                                       min="0"
                                       inputmode="decimal">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Empty State -->
                <div id="no-sizes" class="text-center py-8 {{ $product->sizes->isNotEmpty() ? 'hidden' : '' }}">
                    <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-ruler text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 text-sm">No other sizes added</p>
                    <p class="text-gray-400 text-xs mt-1">100ml size is already configured above</p>
                    <p class="text-gray-400 text-xs mt-1">Tap + to add other sizes like 50ml, 200ml, etc.</p>
                </div>
                
                <!-- Size errors -->
                @error('sizes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('sizes.*.size_ml')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('sizes.*.price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('sizes.*.discount_price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </form>

    <!-- Bottom Action Bar (Mobile) -->
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 shadow-lg z-50">
        <div class="flex items-center justify-between">
            <button type="button" 
                    onclick="if(confirm('Are you sure? All changes will be lost.')) history.back()"
                    class="px-5 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium active:bg-gray-50">
                Cancel
            </button>
            
            <div class="flex items-center space-x-3">
                <button type="submit" 
                        form="productForm"
                        id="submitButton"
                        class="px-6 py-3 bg-black text-white rounded-xl font-medium active:bg-gray-800 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Update Product
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Size Template -->
<template id="sizeTemplate">
    <div class="size-item p-4 border border-gray-200 rounded-xl bg-gray-50">
        <div class="flex justify-between items-start mb-4">
            <h4 class="text-sm font-medium text-gray-900">Additional Size</h4>
            <button type="button" 
                    class="w-8 h-8 flex items-center justify-center text-red-500 active:text-red-700 remove-size"
                    aria-label="Remove size">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="space-y-3">
            <div>
                <label class="block text-xs text-gray-600 mb-1.5">Size (ml) <span class="text-red-500">*</span></label>
                <input type="number" 
                       name="sizes[INDEX_PLACEHOLDER][size_ml]" 
                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-base focus:border-black size-ml-input"
                       placeholder="50"
                       min="1"
                       required
                       inputmode="numeric">
            </div>
            
            <div>
                <label class="block text-xs text-gray-600 mb-1.5">Regular Price ($) <span class="text-red-500">*</span></label>
                <input type="number" 
                       step="0.01"
                       name="sizes[INDEX_PLACEHOLDER][price]" 
                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-base focus:border-black size-price-input"
                       placeholder="0.00"
                       min="0.01"
                       required
                       inputmode="decimal">
            </div>

            <div>
                <label class="block text-xs text-gray-600 mb-1.5">Discount Price ($)</label>
                <input type="number" 
                       step="0.01"
                       name="sizes[INDEX_PLACEHOLDER][discount_price]" 
                       class="w-full px-4 py-3 bg-white border border-gray-300 rounded-lg text-base focus:border-black size-discount-input"
                       placeholder="0.00"
                       min="0"
                       inputmode="decimal">
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prevent zoom on focus (iOS)
    document.querySelectorAll('input, select, textarea').forEach(el => {
        el.addEventListener('focus', function() {
            this.style.fontSize = '16px';
        });
        el.addEventListener('blur', function() {
            this.style.fontSize = '';
        });
    });

    // Image upload handling
    const imageUpload = document.getElementById('main_image');
    const imageUploadArea = document.getElementById('imageUploadArea');
    const imagePreview = document.getElementById('imagePreview');
    const uploadImageBtn = document.getElementById('uploadImageBtn');

    // Single click handler for upload button
    uploadImageBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        imageUpload.click();
    });

    // Prevent double triggering on image upload area
    let clickTimer;
    imageUploadArea.addEventListener('click', function(e) {
        if (e.target === this || e.target === uploadImageBtn) {
            clearTimeout(clickTimer);
            clickTimer = setTimeout(() => {
                imageUpload.click();
            }, 300);
        }
    });

    imageUpload.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // File size validation
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                this.value = '';
                return;
            }
            
            // File type validation
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            if (!validTypes.includes(file.type)) {
                alert('Only JPG, PNG, and GIF images are allowed');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileSize').textContent = formatFileSize(file.size);
                imagePreview.classList.remove('hidden');
                imageUploadArea.classList.add('hidden');
                imageUploadArea.classList.remove('border-red-500');
            };
            reader.readAsDataURL(file);
        }
    });

    // Size management
    let sizeIndex = {{ $product->sizes->count() }};
    const sizesContainer = document.getElementById('sizes-container');
    const noSizes = document.getElementById('no-sizes');
    const sizeTemplate = document.getElementById('sizeTemplate');

    // Function to add size field
    function addSizeField(prefillData = null) {
        if (noSizes) noSizes.classList.add('hidden');
        
        const templateContent = sizeTemplate.innerHTML;
        const html = templateContent.replace(/INDEX_PLACEHOLDER/g, sizeIndex);
        
        const sizeDiv = document.createElement('div');
        sizeDiv.innerHTML = html;
        const sizeElement = sizeDiv.firstElementChild;
        sizesContainer.appendChild(sizeElement);
        
        if (prefillData) {
            const inputs = sizeElement.querySelectorAll('input');
            inputs[0].value = prefillData.size_ml || '';
            inputs[1].value = prefillData.price || '';
            inputs[2].value = prefillData.discount_price || '';
        }
        
        sizeElement.querySelector('.remove-size').addEventListener('click', function() {
            this.closest('.size-item').remove();
            if (sizesContainer.children.length === 0 && noSizes) {
                noSizes.classList.remove('hidden');
            }
        });
        
        sizeIndex++;
        return sizeElement;
    }

    document.getElementById('add-size').addEventListener('click', function() {
        addSizeField();
    });

    // Initialize sizes from old input
    @if(old('sizes') && count(old('sizes')) > 0)
        const oldSizes = @json(old('sizes'));
        sizesContainer.innerHTML = '';
        
        oldSizes.forEach((size) => {
            addSizeField(size);
        });
        
        if (noSizes) {
            noSizes.classList.add('hidden');
        }
    @endif

    // Add remove functionality to existing sizes
    document.querySelectorAll('.remove-size').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.size-item').remove();
            if (sizesContainer.children.length === 0 && noSizes) {
                noSizes.classList.remove('hidden');
            }
        });
    });

    // Form validation
    const form = document.getElementById('productForm');
    const submitButton = document.getElementById('submitButton');
    const originalButtonText = submitButton.innerHTML;

    form.addEventListener('submit', function(e) {
        document.querySelectorAll('.border-red-500').forEach(el => {
            el.classList.remove('border-red-500');
        });
        
        let isValid = true;
        let firstInvalidField = null;

        // Check required fields
        form.querySelectorAll('[required]').forEach(field => {
            if (field.type === 'radio') return;
            
            if (!field.value.trim()) {
                isValid = false;
                if (!firstInvalidField) firstInvalidField = field;
                field.classList.add('border-red-500');
            }
        });

        // Check radio groups
        const genderRadios = document.querySelectorAll('input[name="gender"]:checked');
        if (genderRadios.length === 0) {
            isValid = false;
            document.querySelectorAll('input[name="gender"]').forEach(radio => {
                radio.closest('label').querySelector('div').classList.add('border-red-500');
            });
        }

        const availableRadios = document.querySelectorAll('input[name="available"]:checked');
        if (availableRadios.length === 0) {
            isValid = false;
            document.querySelectorAll('input[name="available"]').forEach(radio => {
                radio.closest('label').querySelector('div').classList.add('border-red-500');
            });
        }

        // Note: Image is optional for edit, so no validation needed

        if (!isValid) {
            e.preventDefault();
            if (firstInvalidField) firstInvalidField.focus();
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'fixed top-4 left-4 right-4 bg-red-100 text-red-700 p-3 rounded-lg shadow-lg text-sm font-medium z-50';
            errorDiv.innerHTML = 'Please fill in all required fields';
            document.body.appendChild(errorDiv);
            
            setTimeout(() => errorDiv.remove(), 4000);
        } else {
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
        }
    });

    form.addEventListener('invalid', function(e) {
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    }, true);
});

function removeImage() {
    document.getElementById('main_image').value = '';
    document.getElementById('imagePreview').classList.add('hidden');
    document.getElementById('imageUploadArea').classList.remove('hidden');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>

<style>
@media (max-width: 640px) {
    input, select, textarea {
        font-size: 16px !important;
        min-height: 44px !important;
    }
    
    button, 
    .btn-touch,
    label[for],
    input[type="checkbox"] + span,
    input[type="radio"] + div {
        min-height: 44px;
        min-width: 44px;
    }
    
    input[type="number"] {
        -moz-appearance: textfield;
    }
    
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    body {
        -webkit-overflow-scrolling: touch;
        overscroll-behavior-y: contain;
    }
    
    .min-h-screen {
        min-height: 100vh;
        min-height: -webkit-fill-available;
    }
    
    input, select, textarea {
        border-radius: 12px;
    }
    
    input:focus, 
    select:focus, 
    textarea:focus {
        outline: none;
        border-color: #000;
        box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
    }
    
    button:active,
    label:active {
        transform: scale(0.98);
        transition: transform 0.1s;
    }
    
    .size-item {
        transition: all 0.2s;
    }
    
    .size-item:active {
        transform: scale(0.99);
    }
}
</style>
@endsection