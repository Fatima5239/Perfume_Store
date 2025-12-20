@extends('layouts.app')

@section('title', 'Premium Perfumes | PERFUME AL WISSAM')

@push('styles')
    <style>
        /* ===== BASE STYLES ===== */
        :root {
            --primary: #000000;
            --secondary: #f5f5f5;
            --accent: #8b7355;
            --text: #333333;
            --text-light: #666666;
            --white: #ffffff;
            --sale: #dc2626;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--text);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-title {
            font-size: 32px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 16px;
            color: var(--primary);
        }

        .section-subtitle {
            text-align: center;
            color: var(--text-light);
            margin-bottom: 48px;
            font-size: 18px;
        }

        .btn {
            display: inline-block;
            background: var(--primary);
            color: var(--white);
            padding: 16px 32px;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            text-align: center;
            cursor: pointer;
        }

        .btn:hover {
            background: var(--white);
            color: var(--primary);
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-secondary:hover {
            background: var(--primary);
            color: var(--white);
        }

        /* ===== HERO SECTION ===== */
        .hero {
            height: 90vh;
            min-height: 700px;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                url("{{ asset('images/homeImage.jpeg') }}") center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: var(--white);
            padding: 20px;
        }

        .hero-content {
            max-width: 800px;
            animation: fadeIn 1s ease;
        }

        .hero h1 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 24px;
            line-height: 1.2;
        }

        .hero p {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .hero .btn {
            min-width: 180px;
        }

        /* ===== CATEGORIES SECTION ===== */
        .categories {
            padding: 80px 0;
            background: var(--secondary);
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 40px;
        }

        .category-card {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            height: 300px;
            text-decoration: none;
            color: var(--white);
        }

        .category-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .category-card:hover img {
            transform: scale(1.05);
        }

        .category-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 30px;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
        }

        .category-content h3 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .category-content p {
            opacity: 0.9;
            font-size: 16px;
        }

        /* ===== FEATURED PRODUCTS ===== */
        .featured-products {
            padding: 80px 0;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 40px;
        }

        .product-card {
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            color: inherit;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            position: relative;
            height: 280px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            background: var(--sale);
            color: var(--white);
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            z-index: 1;
        }

        .product-info {
            padding: 24px;
        }

        .product-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            line-height: 1.4;
            height: 50px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .product-price {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .current-price {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
        }

        .original-price {
            font-size: 16px;
            color: var(--text-light);
            text-decoration: line-through;
        }

        .product-actions {
            display: flex;
            gap: 12px;
        }

        .btn-view {
            flex: 1;
            padding: 12px;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-view:hover {
            background: #333;
        }

        .view-all {
            text-align: center;
            margin-top: 40px;
        }

        /* ===== TRUST SECTION ===== */
        .trust-section {
            padding: 80px 0;
            background: var(--secondary);
        }

        .trust-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
            margin-top: 40px;
        }

        .trust-item {
            text-align: center;
            padding: 40px 20px;
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .trust-item:hover {
            transform: translateY(-5px);
        }

        .trust-icon {
            width: 64px;
            height: 64px;
            background: var(--primary);
            color: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
        }

        .trust-item h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
            color: var(--primary);
        }

        .trust-item p {
            color: var(--text-light);
            font-size: 15px;
        }

        /* ===== NEWSLETTER ===== */
        .newsletter {
            padding: 80px 0;
            background: var(--primary);
            color: var(--white);
            text-align: center;
        }

        .newsletter h2 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 16px;
        }

        .newsletter p {
            font-size: 18px;
            margin-bottom: 40px;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .newsletter-form {
            max-width: 500px;
            margin: 0 auto;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .newsletter-input {
            padding: 16px 24px;
            border-radius: 8px;
            border: none;
            font-size: 16px;
            width: 100%;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (min-width: 768px) {
            .category-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .product-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .trust-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .form-group {
                flex-direction: row;
            }

            .newsletter-input {
                flex: 1;
            }

            .hero h1 {
                font-size: 56px;
            }
        }

        @media (min-width: 1024px) {
            .hero h1 {
                font-size: 64px;
            }

            .section-title {
                font-size: 40px;
            }
        }

        /* Mobile specific */
        @media (max-width: 767px) {
            .hero {
                height: 80vh;
                min-height: 500px;
            }

            .hero h1 {
                font-size: 36px;
            }

            .hero p {
                font-size: 18px;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .hero .btn {
                width: 100%;
                max-width: 300px;
            }

            .category-card {
                height: 200px;
            }

            .category-content h3 {
                font-size: 22px;
            }

            .product-image {
                height: 200px;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .product-card:hover {
                transform: none;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            }

            .category-card:hover img {
                transform: none;
            }

            .trust-item:hover {
                transform: none;
            }

            .btn:hover {
                transform: none;
            }
        }

        /* Loading states */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        /* Empty states */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
            grid-column: 1 / -1;
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state p {
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-content">
            <h1>Discover Your Signature Scent</h1>
            <p>Luxury fragrances that define your personality and style</p>
            <div class="hero-buttons">
                <a href="{{ route('collections') }}" class="btn">Shop now</a>
            </div>
        </div>
    </section>

    <!-- CATEGORIES -->
    <section class="categories">
        <div class="container">
            <h2 class="section-title">Shop By Collection</h2>
            <p class="section-subtitle">Find the perfect fragrance for every occasion</p>

            <div class="category-grid">
                <!-- Women's - Elegant Rose Theme -->
                <a href="{{ route('collections.women') }}" class="category-card">
                    <img src="" alt="Women's Collection">
                    <div class="category-content">
                        <h3>Women's</h3>
                        <p>Elegant & feminine scents</p>
                    </div>
                </a>

                <!-- Men's - Sophisticated Dark Theme -->
                <a href="{{ route('collections.men') }}" class="category-card">
                    <img src="" alt="Men's Collection">
                    <div class="category-content">
                        <h3>Men's</h3>
                        <p>Bold & sophisticated</p>
                    </div>
                </a>

                <!-- Unisex - Modern Minimalist -->
                <a href="{{ route('collections.unisex') }}" class="category-card">
                    <img src="" alt="Unisex Collection">
                    <div class="category-content">
                        <h3>Unisex</h3>
                        <p>Modern & versatile</p>
                    </div>
                </a>

                <!-- All - Luxury Display -->
                <a href="{{ route('collections') }}" class="category-card">
                    <img src="https://images.unsplash.com/photo-1585123334904-89d6f8e5f7b1" alt="All Perfumes">
                    <div class="category-content">
                        <h3>All Perfumes</h3>
                        <p>Browse complete collection</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- FEATURED PRODUCTS -->
    <section class="featured-products">
        <div class="container">
            <h2 class="section-title">Featured Perfumes</h2>

            <div class="product-grid">
                @forelse($featuredProducts as $product)
                    <a href="{{ route('product.show', $product->id) }}" class="product-card">
                        <div class="product-image">
                            @if($product->discount_100ml)
                                <div class="product-badge">SALE</div>
                            @endif

                            @if($product->main_image)
                                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}">
                            @else
                                <img src="https://images.unsplash.com/photo-1585123334904-89d6f8e5f7b1" alt="{{ $product->name }}">
                            @endif
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <div class="product-price">
                                @if($product->discount_100ml)
                                    <span class="current-price">${{ number_format($product->discount_100ml, 2) }}</span>
                                    <span class="original-price">${{ number_format($product->price_100ml, 2) }}</span>
                                @else
                                    <span class="current-price">${{ number_format($product->price_100ml, 2) }}</span>
                                @endif
                            </div>
                            <button class="btn-view">View Details</button>
                        </div>
                    </a>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-perfume-bottle"></i>
                        </div>
                        <p>No featured products available</p>
                        <a href="{{ route('collections') }}" class="btn">Browse Collection</a>
                    </div>
                @endforelse
            </div>

            <div class="view-all">
                <a href="{{ route('collections') }}" class="btn">View All Perfumes</a>
            </div>
        </div>
    </section>

    <!-- TRUST SECTION -->
    <section class="trust-section">
        <div class="container">
            <h2 class="section-title">Why Choose Perfume Al Wissam</h2>
            <p class="section-subtitle">Experience luxury with confidence</p>

            <div class="trust-grid">
                <div class="trust-item">
                    <div class="trust-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3> Authentic</h3>
                    <p>Guaranteed genuine perfumes from original brands</p>
                </div>

                <div class="trust-item">
                    <div class="trust-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Fast Shipping</h3>
                    <p>Free delivery on orders over $50</p>
                </div>

                <div class="trust-item">
                    <div class="trust-icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <h3>Gifts</h3>
                    <p>Beautifully packaged for special occasions</p>
                </div>

                <div class="trust-item">
                    <div class="trust-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Expert Support</h3>
                    <p>Fragrance consultants to help you choose</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Newsletter form submission
            const newsletterForm = document.querySelector('.newsletter-form');
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const emailInput = this.querySelector('.newsletter-input');
                    const button = this.querySelector('.btn');
                    const email = emailInput.value.trim();

                    if (!email) return;

                    // Save original button text
                    const originalText = button.textContent;

                    // Show loading state
                    button.textContent = 'Subscribing...';
                    button.classList.add('loading');

                    // Simulate API call
                    setTimeout(() => {
                        // Show success
                        button.textContent = 'Subscribed!';
                        button.style.background = '#28a745';

                        // Reset after 2 seconds
                        setTimeout(() => {
                            button.textContent = originalText;
                            button.style.background = '';
                            button.classList.remove('loading');
                            emailInput.value = '';
                        }, 2000);
                    }, 1000);
                });
            }

            // Track product views for analytics
            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', function (e) {
                    const productId = this.href.split('/').pop();
                    console.log('Product viewed:', productId);
                    // Add your analytics tracking here
                });
            });

            // Add subtle animations on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animation = 'fadeIn 0.8s ease forwards';
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe sections for animations
            document.querySelectorAll('.categories, .featured-products, .trust-section').forEach(section => {
                observer.observe(section);
            });
        });
    </script>
@endpush