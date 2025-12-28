<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the items.
     */
    public function index()
    {
        $items = Item::latest()->get();
        return view('admin.items.index', compact('items'));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        return view('admin.items.create');
    }

    /**
     * Store a newly created item in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $itemData = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status ?? 'active',
        ];

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
            $itemData['image'] = $imagePath;
        }

        // Handle custom WhatsApp message if provided
        if ($request->filled('whatsapp_message')) {
            $itemData['whatsapp_message'] = $request->whatsapp_message;
        }

        Item::create($itemData);

        return redirect()->route('admin.items.index')->with('success', 'Item created successfully.');
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Item $item)
    {
        return view('admin.items.edit', compact('item'));
    }

    /**
     * Update the specified item in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update image if provided
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($item->image && \Storage::disk('public')->exists($item->image)) {
                \Storage::disk('public')->delete($item->image);
            }
            $imagePath = $request->file('image')->store('items', 'public');
            $item->image = $imagePath;
        }

        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->status = $request->status ?? 'active';
        
        // Update custom WhatsApp message if provided
        if ($request->filled('whatsapp_message')) {
            $item->whatsapp_message = $request->whatsapp_message;
        }
        
        // Slug will be auto-updated if name changed
        if ($item->isDirty('name')) {
            $item->slug = null;
        }
        
        $item->save();

        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified item from storage.
     */
    public function destroy(Item $item)
    {
        // Delete image
        if ($item->image && \Storage::disk('public')->exists($item->image)) {
            \Storage::disk('public')->delete($item->image);
        }
        
        $item->delete();
        
        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully.');
    }
}