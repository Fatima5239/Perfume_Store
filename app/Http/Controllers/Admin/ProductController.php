<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Fragrance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['brand', 'fragrances', 'sizes'])
            ->latest()
            ->paginate(10);
            
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $brands = Brand::where('status', 'active')->get(['id', 'name']);
        $fragrances = Fragrance::all(['id', 'name']);
        
        return view('admin.products.create', compact('brands', 'fragrances'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string', // Changed from required
            'fragrance_notes' => 'nullable|string|max:500',
            'gender' => 'required|in:men,women,unisex',
            'available' => 'required|in:0,1', // Added
            'price_100ml' => 'required|numeric|min:0.01|max:999999.99', // Changed from 'price'
            'discount_100ml' => 'nullable|numeric|min:0|lt:price_100ml', // Changed from 'discount_price'
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'fragrances' => 'nullable|array',
            'fragrances.*' => 'exists:fragrances,id',
            'sizes' => 'nullable|array',
            'sizes.*.size_ml' => 'required|integer|min:1|max:1000',
            'sizes.*.price' => 'required|numeric|min:0.01|max:999999.99',
            'sizes.*.discount_price' => 'nullable|numeric|min:0|lt:sizes.*.price', // Added
        ], [
            'discount_100ml.lt' => '100ml discount price must be less than the original price.',
            'sizes.*.discount_price.lt' => 'Size discount price must be less than the regular price.',
            'main_image.required' => 'Product main image is required.',
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                // Create product
                $product = Product::create([
                    'name' => $validated['name'],
                    'brand_id' => $validated['brand_id'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'fragrance_notes' => $validated['fragrance_notes'] ?? null,
                    'gender' => $validated['gender'],
                    'available' => $validated['available'],
                    'price_100ml' => $validated['price_100ml'],
                    'discount_100ml' => $validated['discount_100ml'] ?? null,
                    'status' => 'active',
                    'main_image' => $request->file('main_image')->store('products', 'public'),
                ]);

                // Sync fragrances
                if (!empty($validated['fragrances'])) {
                    $product->fragrances()->sync($validated['fragrances']);
                }

                // Create sizes (optional)
                if (!empty($validated['sizes'])) {
                    foreach ($validated['sizes'] as $size) {
                        $product->sizes()->create([
                            'size_ml' => $size['size_ml'],
                            'price' => $size['price'],
                            'discount_price' => $size['discount_price'] ?? null,
                        ]);
                    }
                }
            });

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            \Log::error('Product creation error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Failed to create product. Please try again.');
        }
    }

    public function edit(Product $product)
    {
        $brands = Brand::where('status', 'active')->get(['id', 'name']);
        $fragrances = Fragrance::all(['id', 'name']);
        $product->load(['fragrances', 'sizes']);
        
        return view('admin.products.edit', compact('product', 'brands', 'fragrances'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_id' => 'nullable|exists:brands,id',
            'description' => 'nullable|string',
            'fragrance_notes' => 'nullable|string|max:500',
            'gender' => 'required|in:men,women,unisex',
            'available' => 'required|in:0,1',
            'price_100ml' => 'required|numeric|min:0.01|max:999999.99',
            'discount_100ml' => 'nullable|numeric|min:0|lt:price_100ml',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'fragrances' => 'nullable|array',
            'fragrances.*' => 'exists:fragrances,id',
            'sizes' => 'nullable|array',
            'sizes.*.id' => 'nullable|exists:product_sizes,id',
            'sizes.*.size_ml' => 'required|integer|min:1|max:1000',
            'sizes.*.price' => 'required|numeric|min:0.01|max:999999.99',
            'sizes.*.discount_price' => 'nullable|numeric|min:0|lt:sizes.*.price',
        ], [
            'discount_100ml.lt' => '100ml discount price must be less than the original price.',
            'sizes.*.discount_price.lt' => 'Size discount price must be less than the regular price.',
        ]);

        try {
            DB::transaction(function () use ($validated, $request, $product) {
                // Handle image upload
                $imageData = [];
                if ($request->hasFile('main_image')) {
                    // Delete old image if exists
                    if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
                        Storage::disk('public')->delete($product->main_image);
                    }
                    
                    $imageData['main_image'] = $request->file('main_image')->store('products', 'public');
                }

                // Update product
                $product->update(array_merge([
                    'name' => $validated['name'],
                    'brand_id' => $validated['brand_id'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'fragrance_notes' => $validated['fragrance_notes'] ?? null,
                    'gender' => $validated['gender'],
                    'available' => $validated['available'],
                    'price_100ml' => $validated['price_100ml'],
                    'discount_100ml' => $validated['discount_100ml'] ?? null,
                ], $imageData));

                // Sync fragrances
                $product->fragrances()->sync($validated['fragrances'] ?? []);

                // Handle sizes update
                $existingSizeIds = $product->sizes()->pluck('id')->toArray();
                $inputSizeIds = collect($validated['sizes'] ?? [])
                    ->pluck('id')
                    ->filter()
                    ->toArray();

                // Delete removed sizes
                $sizesToDelete = array_diff($existingSizeIds, $inputSizeIds);
                if (!empty($sizesToDelete)) {
                    $product->sizes()->whereIn('id', $sizesToDelete)->delete();
                }

                // Update or create sizes
                foreach ($validated['sizes'] ?? [] as $size) {
                    if (isset($size['id']) && in_array($size['id'], $existingSizeIds)) {
                        $product->sizes()->where('id', $size['id'])->update([
                            'size_ml' => $size['size_ml'],
                            'price' => $size['price'],
                            'discount_price' => $size['discount_price'] ?? null,
                        ]);
                    } else {
                        $product->sizes()->create([
                            'size_ml' => $size['size_ml'],
                            'price' => $size['price'],
                            'discount_price' => $size['discount_price'] ?? null,
                        ]);
                    }
                }
            });

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Product update error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Failed to update product. Please try again.');
        }
    }

    public function destroy(Product $product)
    {
        try {
            // Delete associated image
            if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
                Storage::disk('public')->delete($product->main_image);
            }

            $product->delete();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Product deletion error: ' . $e->getMessage());
            return back()
                ->with('error', 'Failed to delete product. Please try again.');
        }
    }

    // Add this method after the destroy() method in your ProductController
public function toggleAvailability(Product $product)
{
    try {
        $product->update([
            'available' => !$product->available
        ]);
        
        return back()->with('success', 'Product availability updated successfully.');
    } catch (\Exception $e) {
        \Log::error('Toggle availability error: ' . $e->getMessage());
        return back()->with('error', 'Failed to update product availability.');
    }
}
}