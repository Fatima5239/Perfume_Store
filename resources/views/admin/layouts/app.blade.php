<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') | PERFUME AL WISSAM</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            overflow-x: hidden;
        }
        
        /* Custom scrollbar */
        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: #4a5568 #1a202c;
        }
        
        .sidebar-scroll::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-scroll::-webkit-scrollbar-track {
            background: #1a202c;
        }
        
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background-color: #4a5568;
            border-radius: 3px;
        }
        
        /* Active link indicator */
        .nav-link.active {
            position: relative;
        }
        
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 70%;
            background-color: #f59e0b;
            border-radius: 0 2px 2px 0;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-100">

    <div class="flex">
        <!-- Sidebar -->
        <aside class="bg-black text-white w-64 h-screen fixed left-0 top-0 z-40 hidden md:block overflow-y-auto sidebar-scroll">
            <!-- Logo -->
            <div class="p-5 border-b border-gray-800">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logoImage.png') }}" alt="Logo" class="h-10">
                    <div>
                        <h2 class="text-xl font-bold">PERFUME</h2>
                        <p class="text-xs text-gray-400">Admin Panel</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center gap-3 py-3 px-4 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 active' : 'hover:bg-gray-900' }}">
                            <i class="fas fa-tachometer-alt w-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    
                    <!-- Homepage Link -->
                    <li>
                        <a href="{{ route('home') }}" 
                           target="_blank"
                           class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-gray-900 transition-all duration-200 group">
                            <i class="fas fa-external-link-alt w-5 text-gray-400 group-hover:text-white"></i>
                            <span>View Website</span>
                            <span class="ml-auto text-xs text-gray-500 group-hover:text-gray-300">Home</span>
                        </a>
                    </li>
                    
                    <!-- ========== PRODUCTS SECTION ========== -->
                    <li>
                        <div class="mb-2">
                            <p class="text-xs text-gray-500 px-4 uppercase tracking-wider mb-2">Products Management</p>
                        </div>
                    </li>
                    
                    <li>
                        <a href="{{ route('admin.products.index') }}"
                            class="flex items-center gap-3 py-3 px-4 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-gray-900 active' : 'hover:bg-gray-900' }}">
                            <i class="fas fa-wine-bottle w-5"></i>
                            <span>Perfumes</span>
                        </a>
                    </li>
                    
                    <!-- ========== NEW: GIFT ITEMS SECTION ========== -->
                    <li>
                        <a href="{{ route('admin.items.index') }}"
                            class="flex items-center gap-3 py-3 px-4 rounded-lg {{ request()->routeIs('admin.items.*') ? 'bg-gray-900 active' : 'hover:bg-gray-900' }}">
                            <i class="fas fa-gift w-5"></i>
                            <span>Gift Items</span>
                        </a>
                    </li>
                    <!-- ============================================= -->
                    
                    <!-- Divider -->
                    <li>
                        <div class="pt-2 mt-2 border-t border-gray-800"></div>
                    </li>
                    
                    <!-- Quick Links -->
                    <li>
                        <div class="pt-2">
                            <p class="text-xs text-gray-500 px-4 mb-2 uppercase tracking-wider">Quick Links</p>
                            <a href="{{ route('collections') }}" 
                               target="_blank"
                               class="flex items-center gap-3 py-2 px-4 rounded-lg hover:bg-gray-900 text-sm text-gray-300 hover:text-white">
                                <i class="fas fa-store w-5 text-sm"></i>
                                <span>Collections</span>
                            </a>
                            <a href="{{ route('collections.women') }}" 
                               target="_blank"
                               class="flex items-center gap-3 py-2 px-4 rounded-lg hover:bg-gray-900 text-sm text-gray-300 hover:text-white">
                                <i class="fas fa-female w-5 text-sm"></i>
                                <span>Women's</span>
                            </a>
                            <a href="{{ route('collections.men') }}" 
                               target="_blank"
                               class="flex items-center gap-3 py-2 px-4 rounded-lg hover:bg-gray-900 text-sm text-gray-300 hover:text-white">
                                <i class="fas fa-male w-5 text-sm"></i>
                                <span>Men's</span>
                            </a>
                            
                            <!-- NEW: Gift Items Page Link -->
                            <a href="#" 
                               target="_blank"
                               class="flex items-center gap-3 py-2 px-4 rounded-lg hover:bg-gray-900 text-sm text-gray-300 hover:text-white">
                                <i class="fas fa-gift w-5 text-sm"></i>
                                <span>Gift Items Page</span>
                                <span class="ml-auto">
                                    <i class="fas fa-external-link-alt text-xs text-gray-500"></i>
                                </span>
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <!-- Bottom Section -->
            <div class="absolute bottom-0 w-full border-t border-gray-800 bg-black">
                <!-- Current User Info -->
                <div class="p-4 border-b border-gray-800">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-yellow-600 to-yellow-800 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Logout -->
                <div class="p-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-gray-900 w-full text-red-400 hover:text-red-300 transition-colors">
                            <i class="fas fa-sign-out-alt w-5"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 md:ml-64">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center justify-between px-4 py-3">
                    <!-- Mobile Menu Button -->
                    <button id="menuToggle" class="md:hidden text-gray-700">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Page Title with Breadcrumb -->
                    <div class="flex items-center gap-4">
                        <h1 class="text-lg font-semibold text-gray-800">
                            @yield('page-title', 'Dashboard')
                        </h1>
                        
                        <!-- Quick Home Link on Desktop -->
                        <div class="hidden md:flex items-center gap-2">
                            <span class="text-gray-400">|</span>
                            <a href="{{ route('home') }}" 
                               target="_blank"
                               class="text-sm text-gray-600 hover:text-black flex items-center gap-1 transition-colors">
                                <i class="fas fa-external-link-alt text-xs"></i>
                                Visit Website
                            </a>
                        </div>
                    </div>

                    <!-- User Info with Home Link -->
                    <div class="flex items-center gap-4">
                        <!-- Quick Home Link for Mobile -->
                        <a href="{{ route('home') }}" 
                           target="_blank"
                           class="md:hidden text-gray-700 hover:text-black">
                            <i class="fas fa-home text-lg"></i>
                        </a>
                        
                        <!-- User Info -->
                        <div class="flex items-center gap-3">
                            <div class="hidden md:flex flex-col items-end">
                                <span class="text-sm font-medium">{{ Auth::user()->name }}</span>
                                <span class="text-xs text-gray-500">Administrator</span>
                            </div>
                            <div class="w-8 h-8 bg-gradient-to-r from-yellow-600 to-yellow-800 rounded-full flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 md:p-6">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4 px-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-center md:text-left text-sm text-gray-600 mb-2 md:mb-0">
                        &copy; {{ date('Y') }} PERFUME AL WISSAM
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" 
                           target="_blank"
                           class="text-sm text-gray-600 hover:text-black flex items-center gap-1 transition-colors">
                            <i class="fas fa-external-link-alt text-xs"></i>
                            Go to Homepage
                        </a>
                        <span class="text-gray-300 hidden md:inline">|</span>
                        <span class="text-xs text-gray-500 hidden md:inline">Admin Panel v1.0</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Mobile Sidebar -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden" id="mobileOverlay"></div>
    <aside id="mobileSidebar"
        class="bg-black text-white w-64 h-screen fixed left-0 top-0 z-50 transform -translate-x-full md:hidden transition-transform overflow-y-auto sidebar-scroll">
        <div class="p-5 border-b border-gray-800">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logoImage.png') }}" alt="Logo" class="h-10">
                <div>
                    <h2 class="text-xl font-bold">PERFUME</h2>
                    <p class="text-xs text-gray-400">Admin Panel</p>
                </div>
            </div>
        </div>
        
        <nav class="p-4">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 py-3 px-4 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 active' : 'hover:bg-gray-900' }}">
                        <i class="fas fa-tachometer-alt w-5"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <!-- Homepage Link for Mobile -->
                <li>
                    <a href="{{ route('home') }}" 
                       target="_blank"
                       class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-gray-900 transition-all duration-200 group">
                        <i class="fas fa-home w-5 text-gray-400 group-hover:text-white"></i>
                        <span>Homepage</span>
                        <span class="ml-auto">
                            <i class="fas fa-external-link-alt text-xs text-gray-500"></i>
                        </span>
                    </a>
                </li>
                
                <!-- ========== PRODUCTS SECTION ========== -->
                <li>
                    <div class="mb-2">
                        <p class="text-xs text-gray-500 px-4 uppercase tracking-wider mb-2">Products</p>
                    </div>
                </li>
                
                <li>
                    <a href="{{ route('admin.products.index') }}"
                        class="flex items-center gap-3 py-3 px-4 rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-gray-900 active' : 'hover:bg-gray-900' }}">
                        <i class="fas fa-wine-bottle w-5"></i>
                        <span>Perfumes</span>
                    </a>
                </li>
                
                <!-- ========== NEW: GIFT ITEMS SECTION ========== -->
                <li>
                    <a href="{{ route('admin.items.index') }}"
                        class="flex items-center gap-3 py-3 px-4 rounded-lg {{ request()->routeIs('admin.items.*') ? 'bg-gray-900 active' : 'hover:bg-gray-900' }}">
                        <i class="fas fa-gift w-5"></i>
                        <span>Gift Items</span>
                    </a>
                </li>
                <!-- ============================================= -->
                
                <!-- Divider -->
                <li>
                    <div class="pt-2 mt-2 border-t border-gray-800"></div>
                </li>
                
                <!-- Quick Links for Mobile -->
                <li>
                    <div class="pt-2">
                        <p class="text-xs text-gray-500 px-4 mb-2 uppercase tracking-wider">Quick Links</p>
                        <a href="{{ route('collections') }}" 
                           target="_blank"
                           class="flex items-center gap-3 py-2 px-4 rounded-lg hover:bg-gray-900 text-sm text-gray-300 hover:text-white">
                            <i class="fas fa-store w-5 text-sm"></i>
                            <span>All Collections</span>
                        </a>
                        <a href="{{ route('collections.women') }}" 
                           target="_blank"
                           class="flex items-center gap-3 py-2 px-4 rounded-lg hover:bg-gray-900 text-sm text-gray-300 hover:text-white">
                            <i class="fas fa-female w-5 text-sm"></i>
                            <span>Women's Section</span>
                        </a>
                        <a href="{{ route('collections.men') }}" 
                           target="_blank"
                           class="flex items-center gap-3 py-2 px-4 rounded-lg hover:bg-gray-900 text-sm text-gray-300 hover:text-white">
                            <i class="fas fa-male w-5 text-sm"></i>
                            <span>Men's Section</span>
                        </a>
                        <a href="{{ route('collections.unisex') }}" 
                           target="_blank"
                           class="flex items-center gap-3 py-2 px-4 rounded-lg hover:bg-gray-900 text-sm text-gray-300 hover:text-white">
                            <i class="fas fa-venus-mars w-5 text-sm"></i>
                            <span>Unisex Section</span>
                        </a>
                        
                        <!-- NEW: Gift Items Page Link -->
                        <a href="#" 
                           target="_blank"
                           class="flex items-center gap-3 py-2 px-4 rounded-lg hover:bg-gray-900 text-sm text-gray-300 hover:text-white">
                            <i class="fas fa-gift w-5 text-sm"></i>
                            <span>Gift Items Page</span>
                            <span class="ml-auto">
                                <i class="fas fa-external-link-alt text-xs text-gray-500"></i>
                            </span>
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
        
        <!-- Bottom Section for Mobile -->
        <div class="absolute bottom-0 w-full border-t border-gray-800 bg-black">
            <!-- Current User Info -->
            <div class="p-4 border-b border-gray-800">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-r from-yellow-600 to-yellow-800 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Logout for Mobile -->
            <div class="p-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 py-3 px-4 rounded-lg hover:bg-gray-900 w-full text-red-400 hover:text-red-300 transition-colors">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');

        menuToggle.addEventListener('click', () => {
            mobileSidebar.classList.toggle('-translate-x-full');
            mobileOverlay.classList.toggle('hidden');
            document.body.style.overflow = mobileSidebar.classList.contains('-translate-x-full') ? 'auto' : 'hidden';
        });

        mobileOverlay.addEventListener('click', () => {
            mobileSidebar.classList.add('-translate-x-full');
            mobileOverlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
        });

        document.querySelectorAll('#mobileSidebar a').forEach(link => {
            link.addEventListener('click', () => {
                mobileSidebar.classList.add('-translate-x-full');
                mobileOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            });
        });

        // Close sidebar on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !mobileSidebar.classList.contains('-translate-x-full')) {
                mobileSidebar.classList.add('-translate-x-full');
                mobileOverlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        });

        // Auto-dismiss alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>