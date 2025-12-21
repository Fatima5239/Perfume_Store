@extends('layouts.app')

@section('title', htmlspecialchars($product->name) . ' | PERFUME AL WISSAM')

@push('styles')
<style>
    .product-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    
    .breadcrumb {
        margin-bottom: 30px;
        font-size: 14px;
        color: #666;
    }
    
    .breadcrumb a {
        color: #666;
        text-decoration: none;
    }
    
    .breadcrumb a:hover {
        color: #000;
        text-decoration: underline;
    }
    
    .product-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        margin-bottom: 60px;
    }
    
    @media (max-width: 992px) {
        .product-details {
            grid-template-columns: 1fr;
            gap: 30px;
        }
    }
    
    .product-images {
        position: relative;
    }
    
    .main-image {
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .main-image img {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .product-info {
        padding: 10px 0;
    }
    
    .product-brand {
        color: #888;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 10px;
    }
    
    .product-title {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #333;
    }
    
    .product-price-section {
        margin-bottom: 25px;
        padding-bottom: 25px;
        border-bottom: 1px solid #eee;
    }
    
    .price-container {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }
    
    .current-price {
        font-size: 28px;
        font-weight: 700;
        color: #000;
    }
    
    .original-price {
        font-size: 20px;
        color: #999;
        text-decoration: line-through;
    }
    
    .discount-badge {
        background: #ff6b6b;
        color: white;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .product-description {
        margin-bottom: 30px;
        line-height: 1.6;
        color: #555;
    }
    
    .fragrance-notes {
        margin-bottom: 30px;
    }
    
    .fragrance-notes h3 {
        font-size: 16px;
        margin-bottom: 10px;
        color: #333;
    }
    
    .sizes-section {
        margin-bottom: 30px;
    }
    
    .sizes-section h3 {
        font-size: 16px;
        margin-bottom: 15px;
        color: #333;
    }
    
    .sizes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
    }
    
    .size-option {
        border: 2px solid #ddd;
        border-radius: 6px;
        padding: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }
    
    .size-option:hover {
        border-color: #000;
    }
    
    .size-option.selected {
        border-color: #25D366;
        background: #f8fffa;
    }
    
    .size-option.default-size.selected::after {
        content: '✓';
        position: absolute;
        top: -8px;
        right: -8px;
        background: #25D366;
        color: white;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }
    
    .size-ml {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .size-option.selected .size-ml {
        color: #25D366;
    }
    
    .size-price {
        color: #333;
        font-size: 14px;
    }
    
    .action-buttons {
        margin-top: 30px;
    }
    
    /* WhatsApp Button Styles */
    .contact-whatsapp-btn {
        width: 100%;
        padding: 16px;
        background: #25D366; 
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 10px;
        text-align: center;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    
    .contact-whatsapp-btn:hover {
        background: #1da851;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3);
    }
    
    .whatsapp-icon {
        font-size: 20px;
    }
    
    .btn-text-en {
        display: none;
    }
    
    .language-toggle {
        text-align: center;
        margin-top: 10px;
        font-size: 14px;
    }
    
    .language-toggle a {
        color: #666;
        text-decoration: none;
        padding: 5px 10px;
        border-radius: 4px;
        transition: all 0.3s;
    }
    
    .language-toggle a:hover {
        background: #f5f5f5;
        color: #333;
    }
    
    .related-products {
        margin-top: 60px;
    }
    
    .related-title {
        font-size: 24px;
        margin-bottom: 30px;
        text-align: center;
    }
    
    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 25px;
    }
    
    /* Product card styles for related products */
    .related-grid .product-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: transform 0.3s;
    }
    
    .related-grid .product-card:hover {
        transform: translateY(-5px);
    }
    
    .related-grid .product-image {
        height: 150px;
        overflow: hidden;
    }
    
    .related-grid .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .related-grid .product-info {
        padding: 15px;
    }
    
    .related-grid .product-name {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }
    
    .related-grid .view-product {
        display: block;
        width: 100%;
        padding: 8px;
        background: #000;
        color: white;
        text-align: center;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
    }
    
    @media (max-width: 768px) {
        .product-title {
            font-size: 24px;
        }
        
        .current-price {
            font-size: 24px;
        }
        
        .related-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
        
        .contact-whatsapp-btn {
            padding: 14px;
            font-size: 15px;
        }
        
        .size-option.default-size.selected::after {
            width: 18px;
            height: 18px;
            font-size: 10px;
            top: -6px;
            right: -6px;
        }
    }
</style>
@endpush

@section('content')
<div class="product-container">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> &gt;
        <a href="{{ route('collections') }}">Collections</a> &gt;
        <a href="{{ route('collections.' . $product->gender) }}">{{ ucfirst($product->gender) }}</a> &gt;
        <span>{{ htmlspecialchars($product->name) }}</span>
    </div>
    
    <!-- Product Details -->
    <div class="product-details">
        <!-- Product Image -->
        <div class="product-images">
            <div class="main-image">
                <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : 'https://images.unsplash.com/photo-1585123334904-89d6f8e5f7b1' }}" 
                     alt="{{ htmlspecialchars($product->name) }}" loading="lazy">
            </div>
        </div>
        
        <!-- Product Information -->
        <div class="product-info">
            <div class="product-brand">{{ htmlspecialchars($product->brand->name ?? 'No Brand') }}</div>
            <h1 class="product-title">{{ htmlspecialchars($product->name) }}</h1>
            
            <!-- Price Section -->
            <div class="product-price-section">
                <div class="price-container">
                    @if($product->discount_100ml)
                        <span class="current-price">${{ number_format($product->discount_100ml, 2) }}</span>
                        <span class="original-price">${{ number_format($product->price_100ml, 2) }}</span>
                        @if($product->price_100ml > 0)
                            <span class="discount-badge">
                                Save {{ round((($product->price_100ml - $product->discount_100ml) / $product->price_100ml) * 100) }}%
                            </span>
                        @endif
                    @else
                        <span class="current-price">${{ number_format($product->price_100ml, 2) }}</span>
                    @endif
                </div>
                <div style="font-size: 14px; color: #666; margin-top: 5px;">
                    Price shown is for 100ml (standard size)
                </div>
            </div>
            
            <!-- Description -->
            @if($product->description)
                <div class="product-description">
                    <h3 style="font-size: 16px; margin-bottom: 10px;">Description</h3>
                    <p>{{ htmlspecialchars($product->description) }}</p>
                </div>
            @endif
            
            <!-- Fragrance Notes -->
            @if($product->fragrance_notes)
                <div class="fragrance-notes">
                    <h3>Fragrance Notes</h3>
                    <p>{{ htmlspecialchars($product->fragrance_notes) }}</p>
                </div>
            @endif
            
            <!-- Available Sizes -->
            @if($product->sizes->count() > 0)
                <div class="sizes-section">
                    <h3>Available Sizes</h3>
                    <div class="sizes-grid">
                        @foreach($product->sizes as $size)
                            <div class="size-option {{ $size->size_ml == 100 ? 'default-size' : '' }}" 
                                 data-size="{{ $size->size_ml }}" 
                                 data-price="{{ $size->discount_price ?? $size->price }}">
                                <div class="size-ml">{{ $size->size_ml }}ml</div>
                                <div class="size-price">
                                    ${{ number_format($size->discount_price ?? $size->price, 2) }}
                                    @if($size->discount_price)
                                        <div style="font-size: 12px; color: #999; text-decoration: line-through;">
                                            ${{ number_format($size->price, 2) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div style="font-size: 14px; color: #666; margin-top: 10px;">
                        <span style="color: #25D366;">✓</span> 100ml is auto-selected as standard size
                    </div>
                </div>
            @endif
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="javascript:void(0);" 
                   onclick="openWhatsApp()" 
                   class="contact-whatsapp-btn" 
                   id="whatsappBtn">
                    <span class="whatsapp-icon">📱</span>
                    <span class="btn-text">Contact for 100ml via WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="related-products">
            <h2 class="related-title">You May Also Like</h2>
            <div class="related-grid">
                @foreach($relatedProducts as $related)
                    <div class="product-card">
                        <div class="product-image">
                            <img src="{{ $related->main_image ? asset('storage/' . $related->main_image) : 'https://images.unsplash.com/photo-1585123334904-89d6f8e5f7b1' }}" 
                                 alt="{{ htmlspecialchars($related->name) }}" loading="lazy">
                        </div>
                        <div class="product-info">
                            <div class="product-name">{{ htmlspecialchars($related->name) }}</div>
                            <div class="product-price">
                                @if($related->discount_100ml)
                                    <span style="font-size: 16px; font-weight: 700;">${{ number_format($related->discount_100ml, 2) }}</span>
                                @else
                                    <span style="font-size: 16px; font-weight: 700;">${{ number_format($related->price_100ml, 2) }}</span>
                                @endif
                            </div>
                            <a href="{{ route('product.show', $related->id) }}" class="view-product">
                                View Product
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
// WhatsApp functionality
function openWhatsApp() {
   
    const selectedSize = document.querySelector('.size-option.selected');
    let sizeText = '';
    
    if (selectedSize) {
        const sizeMl = selectedSize.querySelector('.size-ml').textContent;
        const priceElement = selectedSize.querySelector('.size-price');
        const priceText = priceElement ? priceElement.firstChild.textContent.trim() : '';
        sizeText = `Size: ${sizeMl}${priceText ? ` | Price: ${priceText}` : ''}`;
    } else {
        // Default to 100ml if nothing selected
        sizeText = `Size: 100ml | Price: ${{ number_format($product->discount_100ml ?? $product->price_100ml, 2) }} (Standard)`;
    }
    
    // Product information
    const productName = "{{ addslashes($product->name) }}";
    const brandName = "{{ addslashes($product->brand->name ?? '') }}";
    

    const message = `AL WISSAM PERFUMES - NEW INQUIRY
=========================================

PRODUCT INFORMATION
=========================================
• Perfume: ${productName}
${brandName ? `• Brand: ${brandName}` : ''}
• Collection: {{ ucfirst($product->gender) }}

SIZE REQUESTED
=========================================
${sizeText}


PRODUCT REFERENCE
=========================================
• Link: {{ url()->current() }}
• Product ID: #{{ $product->id }}

ARABIC / بالعربية
=========================================
• أرغب في الاستفسار عن: ${productName}
• ${sizeText.replace('Size: ', 'الحجم: ').replace(' | Price: ', ' | السعر: ').replace(' (Standard)', ' (قياسي)')}

CONTACT INFORMATION
=========================================
• WhatsApp: +961 XX XXX XXX
• Response: As soon as possible
• Location: Beirut, Lebanon

Thank you for your interest in Al Wissam Perfumes!`;
    
   
    const whatsappNumber = '961'; 
    
  
    const encodedMessage = encodeURIComponent(message);
    

    window.open(`https://wa.me/${whatsappNumber}?text=${encodedMessage}`, '_blank');
}


document.querySelectorAll('.size-option').forEach(option => {
    option.addEventListener('click', function() {
        const isCurrentlySelected = this.classList.contains('selected');
        const sizeMl = this.querySelector('.size-ml').textContent;
        const whatsappBtn = document.getElementById('whatsappBtn');
        
        // If clicking 100ml, always keep it selected
        if (sizeMl === '100ml' || sizeMl === '100') {
            // Remove selected from all
            document.querySelectorAll('.size-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            // Select 100ml
            this.classList.add('selected');
            whatsappBtn.querySelector('.btn-text').textContent = `Contact for 100ml via WhatsApp`;
            return;
        }
        
        // For other sizes: toggle selection
        if (!isCurrentlySelected) {
            // Select this size, deselect others
            document.querySelectorAll('.size-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            this.classList.add('selected');
            whatsappBtn.querySelector('.btn-text').textContent = `Contact for ${sizeMl} via WhatsApp`;
        } else {
            
            this.classList.remove('selected');
            // Find and select 100ml
            const size100ml = Array.from(document.querySelectorAll('.size-option')).find(opt => {
                const ml = opt.querySelector('.size-ml').textContent;
                return ml === '100ml' || ml === '100';
            });
            if (size100ml) {
                size100ml.classList.add('selected');
            }
            whatsappBtn.querySelector('.btn-text').textContent = `Contact for 100ml via WhatsApp`;
        }
    });
});

// Auto-select 100ml as default on page load - FIXED
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const sizeOptions = document.querySelectorAll('.size-option');
        const whatsappBtn = document.getElementById('whatsappBtn');
        
        // Find 100ml exactly
        let size100ml = null;
        
        sizeOptions.forEach(option => {
            const sizeMl = option.querySelector('.size-ml').textContent.trim();
            // Exact match for 100ml
            if (sizeMl === '100ml' || sizeMl === '100') {
                size100ml = option;
            }
        });
        
        // Always select 100ml if it exists
        if (size100ml) {
            // Remove any selections
            sizeOptions.forEach(opt => opt.classList.remove('selected'));
            // Select 100ml
            size100ml.classList.add('selected');
            
            // Update button
            if (whatsappBtn) {
                whatsappBtn.querySelector('.btn-text').textContent = 'Contact for 100ml via WhatsApp';
            }
        }
    }, 100);
});
</script>
@endpush
@endsection