<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Premium perfumes and luxury fragrances at PERFUME AL WISSAM">
    
    <title>@yield('title', 'Premium Perfumes | PERFUME AL WISSAM')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logoImage.png') }}" type="image/png">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Base Styles -->
    <style>
        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        body {
            font-family: "Poppins", -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #fff;
            color: #000;
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        /* Accessibility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        
        /* Focus styles */
        a:focus,
        button:focus {
            outline: 2px solid #d4af37;
            outline-offset: 2px;
        }
        
        /* Skip to main content for accessibility */
        .skip-to-main {
            position: absolute;
            top: -40px;
            left: 0;
            background: #000;
            color: white;
            padding: 8px 16px;
            z-index: 9999;
            text-decoration: none;
        }
        
        .skip-to-main:focus {
            top: 0;
        }
        
        /* NAVBAR */
        header {
            background: #000;
            padding: 15px 30px;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .logo-title {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }
        
        .logo-title img {
            height: 45px;
            width: auto;
        }
        
        .logo-title h2 {
            font-size: 22px;
            letter-spacing: 3px;
            margin: 0;
            font-weight: 700;
            color: white;
        }
        
        nav {
            display: flex;
            gap: 25px;
            align-items: center;
        }
        
        nav a {
            color: white;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: color 0.3s ease;
            position: relative;
            padding: 5px 0;
        }
        
        nav a:hover {
            color: #d4af37;
        }
        
        nav a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #d4af37;
            transition: width 0.3s ease;
        }
        
        nav a:hover::after {
            width: 100%;
        }
        
        /* Professional Admin Section Styles */
        .admin-controls {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-left: 15px;
            padding-left: 20px;
            border-left: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .admin-badge {
            background: linear-gradient(135deg, #d4af37 0%, #a67c00 100%);
            color: #000;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(212, 175, 55, 0.2);
        }
        
        .admin-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }
        
        .admin-badge i {
            font-size: 14px;
        }
        
        .admin-profile {
            position: relative;
        }
        
        .profile-toggle {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
        }
        
        .profile-toggle:hover {
            background: rgba(255, 255, 255, 0.08);
        }
        
        .profile-toggle i {
            font-size: 12px;
            transition: transform 0.3s ease;
        }
        
        .profile-toggle.active i {
            transform: rotate(180deg);
        }
        
        .profile-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d4af37 0%, #a67c00 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            color: #000;
        }
        
        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            background: white;
            border-radius: 12px;
            min-width: 240px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            z-index: 1001;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            pointer-events: none;
        }
        
        .profile-dropdown.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
        
        .profile-header {
            padding: 16px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #eee;
        }
        
        .profile-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .profile-details h4 {
            font-size: 14px;
            font-weight: 600;
            color: #000;
            margin-bottom: 2px;
        }
        
        .profile-details p {
            font-size: 12px;
            color: #666;
        }
        
        .profile-role {
            display: inline-block;
            background: #d4af37;
            color: #000;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .dropdown-divider {
            height: 1px;
            background: #eee;
            margin: 8px 0;
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #333;
            text-decoration: none;
            transition: background 0.2s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-size: 14px;
        }
        
        .dropdown-item:hover {
            background: #f8f9fa;
        }
        
        .dropdown-item i {
            color: #666;
            font-size: 14px;
            width: 16px;
            text-align: center;
        }
        
        .dropdown-item.logout {
            color: #dc3545;
        }
        
        .dropdown-item.logout:hover {
            background: #ffe6e6;
        }
        
        .dropdown-item.logout i {
            color: #dc3545;
        }
        
        /* MOBILE MENU */
        .hamburger {
            display: none;
            font-size: 26px;
            cursor: pointer;
            background: none;
            border: none;
            color: white;
            padding: 5px;
            transition: transform 0.3s ease;
        }
        
        .hamburger:hover {
            color: #d4af37;
        }
        
        .hamburger:active {
            transform: scale(0.95);
        }
        
        /* Main Content */
        main {
            min-height: calc(100vh - 120px);
        }
        
        /* Footer */
        footer {
            background: #000;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .footer-links {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        
        .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: #d4af37;
        }
        
        .copyright {
            margin-top: 30px;
            color: #999;
            font-size: 14px;
        }
        
        /* MOBILE RESPONSIVE */
        @media (max-width: 768px) {
            header {
                padding: 12px 20px;
            }
            
            .logo-title img {
                height: 35px;
            }
            
            .logo-title h2 {
                font-size: 18px;
                letter-spacing: 2px;
            }
            
            nav {
                display: none;
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                background: #000;
                width: 100%;
                flex-direction: column;
                padding: 20px 0;
                gap: 0;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }
            
            nav.show {
                display: flex;
            }
            
            nav a {
                padding: 15px 20px;
                width: 100%;
                text-align: center;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                font-size: 16px;
            }
            
            nav a:first-child {
                border-top: none;
            }
            
            .admin-controls {
                flex-direction: column;
                border-left: none;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                padding-left: 0;
                padding-top: 15px;
                margin-left: 0;
                width: 100%;
                gap: 10px;
            }
            
            .admin-badge {
                width: 100%;
                justify-content: center;
                padding: 10px;
                font-size: 14px;
            }
            
            .profile-toggle {
                width: 100%;
                justify-content: center;
                padding: 10px;
            }
            
            .profile-dropdown {
                position: static;
                width: 100%;
                margin-top: 10px;
                opacity: 1;
                transform: none;
                pointer-events: auto;
                display: none;
            }
            
            .profile-dropdown.show {
                display: block;
            }
            
            .hamburger {
                display: block;
            }
            
            footer {
                padding: 30px 15px;
            }
            
            .footer-links {
                flex-direction: column;
                gap: 15px;
            }
        }
        
        /* Tablet */
        @media (min-width: 769px) and (max-width: 1024px) {
            nav {
                gap: 20px;
            }
            
            nav a {
                font-size: 14px;
            }
            
            .admin-controls {
                gap: 12px;
            }
            
            .admin-badge {
                font-size: 11px;
                padding: 5px 10px;
            }
        }
        
        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }
            
            nav a,
            .hamburger,
            .admin-badge,
            .profile-toggle {
                transition: none;
            }
        }
        
        /* Print styles */
        @media print {
            header,
            footer,
            .hamburger {
                display: none;
            }
        }
    </style>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Page-specific styles -->
    @stack('styles')
</head>

<body>
    <!-- Skip to main content (Accessibility) -->
    <a href="#main-content" class="skip-to-main">Skip to main content</a>
    
    <!-- NAVBAR -->
    <header>
        <a href="{{ url('/') }}" class="logo-title" aria-label="PERFUME AL WISSAM - Home">
            <img src="{{ asset('images/logoImage.png') }}" alt="PERFUME AL WISSAM Logo" loading="lazy">
            <h2>PERFUME AL WISSAM</h2>
        </a>

        <button class="hamburger" onclick="toggleMenu()" aria-label="Toggle navigation menu" aria-expanded="false">
            ☰
        </button>

        <nav id="navMenu" aria-label="Main navigation">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('collections') }}">All Collections</a>
            <a href="{{ route('collections.women') }}">Women's Section</a>
            <a href="{{ route('collections.men') }}">Men's Section</a>
            <a href="{{ route('collections.unisex') }}">Unisex Section</a>
            
            <!-- Admin Authentication Section - Only shows when admin is logged in -->
            @auth
                @if(Auth::user()->isAdmin())
                <div class="admin-controls">
                    <!-- Admin Dashboard Link -->
                    <a href="{{ route('admin.dashboard') }}" class="admin-badge">
                        <i class="fas fa-shield-alt"></i>
                        Admin Panel
                    </a>
                    
                    <!-- Admin Profile Dropdown -->
                    <div class="admin-profile">
                        <button class="profile-toggle" onclick="toggleProfileDropdown()" aria-label="Open admin menu" aria-expanded="false">
                            <div class="profile-avatar">
                                {{ strtoupper(substr(Auth::user()->name ?: Auth::user()->email, 0, 1)) }}
                            </div>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        
                        <div class="profile-dropdown" id="profileDropdown">
                            <div class="profile-header">
                                <div class="profile-info">
                                    <div class="profile-avatar">
                                        {{ strtoupper(substr(Auth::user()->name ?: Auth::user()->email, 0, 1)) }}
                                    </div>
                                    <div class="profile-details">
                                        <h4>{{ Auth::user()->name ?: Auth::user()->email }}</h4>
                                        <p>{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                                <span class="profile-role">Administrator</span>
                            </div>
                            
                            <div class="dropdown-divider"></div>
                            
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                <i class="fas fa-tachometer-alt"></i>
                                Dashboard
                            </a>
                            
                            <a href="{{ route('admin.products.index') }}" class="dropdown-item">
                                <i class="fas fa-box"></i>
                                Products
                            </a>
                            
                            <div class="dropdown-divider"></div>
                            
                            <!-- Logout form -->
                            <form action="{{ route('logout') }}" method="POST" class="dropdown-item logout">
                                @csrf
                                <button type="submit" class="dropdown-item logout" style="border: none; padding: 0; background: none;">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            @endauth
        </nav>
    </header>

    <!-- MAIN CONTENT AREA -->
    <main id="main-content">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('collections') }}">Collections</a>
                <a href="{{ route('collections.women') }}">Women</a>
                <a href="{{ route('collections.men') }}">Men</a>
                <a href="{{ route('collections.unisex') }}">Unisex</a>
            </div>
            <div class="copyright">
                &copy; {{ date('Y') }} PERFUME AL WISSAM. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // Enhanced mobile menu toggle
        function toggleMenu() {
            const nav = document.getElementById('navMenu');
            const hamburger = document.querySelector('.hamburger');
            const isExpanded = hamburger.getAttribute('aria-expanded') === 'true';
            
            // Toggle menu visibility
            nav.classList.toggle('show');
            
            // Update ARIA attributes
            hamburger.setAttribute('aria-expanded', !isExpanded);
            hamburger.setAttribute('aria-label', !isExpanded ? 'Close navigation menu' : 'Open navigation menu');
            
            // Prevent body scroll when menu is open
            document.body.style.overflow = !isExpanded ? 'hidden' : '';
            
            // Close profile dropdown when opening mobile menu
            if (window.innerWidth <= 768) {
                const profileDropdown = document.getElementById('profileDropdown');
                const profileToggle = document.querySelector('.profile-toggle');
                if (profileDropdown && profileToggle) {
                    profileDropdown.classList.remove('show');
                    profileToggle.classList.remove('active');
                    profileToggle.setAttribute('aria-expanded', 'false');
                }
            }
        }
        
        // Profile dropdown toggle
        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            const toggle = document.querySelector('.profile-toggle');
            const isMobile = window.innerWidth <= 768;
            
            if (isMobile) {
                // On mobile, toggle visibility
                dropdown.classList.toggle('show');
                toggle.classList.toggle('active');
                const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', !isExpanded);
            } else {
                // On desktop, toggle with animation
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                    toggle.classList.remove('active');
                    toggle.setAttribute('aria-expanded', 'false');
                } else {
                    dropdown.classList.add('show');
                    toggle.classList.add('active');
                    toggle.setAttribute('aria-expanded', 'true');
                }
            }
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const nav = document.getElementById('navMenu');
            const hamburger = document.querySelector('.hamburger');
            const profileDropdown = document.getElementById('profileDropdown');
            const profileToggle = document.querySelector('.profile-toggle');
            
            // Close mobile nav menu when clicking outside
            if (window.innerWidth <= 768 && 
                nav.classList.contains('show') && 
                !nav.contains(event.target) && 
                !hamburger.contains(event.target)) {
                toggleMenu();
            }
            
            // Close profile dropdown when clicking outside
            if (profileDropdown && profileToggle && 
                !profileDropdown.contains(event.target) && 
                !profileToggle.contains(event.target)) {
                
                if (profileDropdown.classList.contains('show')) {
                    profileDropdown.classList.remove('show');
                    profileToggle.classList.remove('active');
                    profileToggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
        
        // Close menu on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const nav = document.getElementById('navMenu');
                const profileDropdown = document.getElementById('profileDropdown');
                const profileToggle = document.querySelector('.profile-toggle');
                
                if (nav && nav.classList.contains('show')) {
                    toggleMenu();
                }
                
                if (profileDropdown && profileDropdown.classList.contains('show')) {
                    profileDropdown.classList.remove('show');
                    profileToggle.classList.remove('active');
                    profileToggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
        
        // Reset on window resize
        window.addEventListener('resize', function() {
            const nav = document.getElementById('navMenu');
            const hamburger = document.querySelector('.hamburger');
            const profileDropdown = document.getElementById('profileDropdown');
            const profileToggle = document.querySelector('.profile-toggle');
            
            if (window.innerWidth > 768) {
                // Reset mobile menu
                if (nav && nav.classList.contains('show')) {
                    nav.classList.remove('show');
                    hamburger.setAttribute('aria-expanded', 'false');
                    hamburger.setAttribute('aria-label', 'Open navigation menu');
                    document.body.style.overflow = '';
                }
            } else {
                // On mobile resize, ensure desktop dropdown is hidden
                if (profileDropdown && profileDropdown.classList.contains('show')) {
                    profileDropdown.classList.remove('show');
                    if (profileToggle) {
                        profileToggle.classList.remove('active');
                        profileToggle.setAttribute('aria-expanded', 'false');
                    }
                }
            }
        });
        
        // Handle clicks inside dropdown
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                const nav = document.getElementById('navMenu');
                const target = event.target;
                
                // Close mobile menu when clicking on dropdown items (except dropdown toggle)
                if (nav.classList.contains('show') && 
                    (target.closest('.dropdown-item') || 
                     target.closest('.admin-badge'))) {
                    
                    if (!target.closest('.profile-toggle')) {
                        setTimeout(() => {
                            toggleMenu();
                        }, 300);
                    }
                }
            }
        });
        
        // Initialize ARIA attributes
        document.addEventListener('DOMContentLoaded', function() {
            const profileToggle = document.querySelector('.profile-toggle');
            if (profileToggle) {
                profileToggle.setAttribute('aria-expanded', 'false');
            }
        });
    </script>
    
    <!-- Page-specific scripts -->
    @stack('scripts')
</body>
</html>