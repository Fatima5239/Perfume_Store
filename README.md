# Perfume Al Wissam

A modern, fully-featured e-commerce perfume store built with Laravel where customers browse as guests and contact the owner directly via WhatsApp for purchases. Complete with real-time analytics, advanced search filtering, and secure admin management.

## 🚀 Features

### 🏪 **Core Shop Features**
- **Product Collections**: Separate pages for Men's, Women's, Unisex, and All Collections with advanced filtering
- **Product Display**: Detailed product pages with fragrance notes, multiple sizes, and related products
- **Smart Search**: Live search suggestions with rate limiting and search history analytics
- **Dynamic Homepage**: Hero section, category navigation, and randomly featured perfumes

### 🔍 **Advanced Search & Filtering**
- **Responsive Design**: Mobile-first interface with desktop sidebar and mobile drawer
- **Price Range Slider**: Interactive slider with dual handles for precise price filtering
- **Live Search**: Real-time search with debouncing and loading indicators
- **Active Filter Tracking**: Visual display of applied filters with easy removal
- **Sort Options**: Multiple sorting methods (newest, price low-high, name A-Z)
- **Mobile Gestures**: Swipe-to-close functionality for filter drawer
- **Loading States**: Skeleton loaders and subtle loading indicators for smooth UX
- **Pagination Support**: Integrated with Laravel pagination

### 💬 **Direct Purchase System**
- **WhatsApp Integration**: Customers contact owner directly about specific perfumes
- **Product-Specific Contact**: Each product has a direct WhatsApp link with product information
- **Zero-Checkout Friction**: No shopping cart or payment processing - direct owner communication
- **Quick Inquiries**: Instant WhatsApp messaging for price inquiries and purchases

### ⚙️ **Admin & Management**
- **Real-time Analytics Dashboard**: Track visitors, popular searches, and product views
- **Complete Product Management**: Full CRUD operations with image upload
- **Brand & Fragrance Management**: Associate products with brands and scent types
- **Quick Stock Management**: Toggle product availability instantly

### 📊 **Analytics & Tracking**
- **Visitor Statistics**: Track page views by session, device type, and page category
- **Search Analytics**: Record search queries with result counts
- **Product View Tracking**: Monitor individual product popularity
- **Real-time Dashboard**: Display today's stats, top searches, and most viewed products

### 👑 **Admin Security**
- **Breeze Authentication**: Dedicated login system exclusively for store administrators
- **Admin-Only Access**: Full authentication required for all administration functions
- **Public-First Design**: All customers browse and shop as guests (no accounts needed)

### 🛡️ **Security & Performance**
- **Input Validation & Sanitization**: Secure form handling and search functionality
- **Rate Limiting**: Protection against abuse on search suggestions
- **Admin Middleware**: Protected admin routes with Breeze authentication
- **XSS Protection**: Escaped outputs and sanitized inputs throughout

## 🛠️ Technology Stack

- **Backend**: Laravel 12.x with Eloquent ORM
- **Frontend**: Laravel Blade Templates, Tailwind CSS
- **Search Component**: Custom Blade component with JavaScript interactivity
- **Admin Security**: Laravel Breeze (administrators only)
- **Database**: MySQL with comprehensive migrations
- **Styling**: Tailwind CSS with responsive design
- **JavaScript**: Vanilla JS for interactive components (no jQuery)
- **Communication**: WhatsApp Business integration for direct customer contact

## 👥 Access Model

### 🛍️ **Customer Experience (100% Guest Access)**
- **No accounts required**: All customers shop as guests
- **Zero friction**: Browse collections, search perfumes, and view products immediately
- **Direct WhatsApp Contact**: Click "Contact on WhatsApp" from any product page
- **Product-specific inquiries**: WhatsApp messages include product name for quick reference
- **All shop features available**: Search, filters, product details - all without login

### 👨‍💼 **Administrator Access**
- **Breeze authentication**: Dedicated login at `/login` for store owners/staff
- **Full admin capabilities**: Product management, inventory, and analytics dashboard
- **Protected routes**: All `/admin/*` paths require Breeze authentication
- **Role separation**: Clear distinction between public shoppers and store management

## 🔍 Advanced Search & Filtering System

Your perfume store features a sophisticated search and filtering component that works across all collection pages.

### **Component Features:**
- **Mobile-Responsive Design**:
  - Desktop: Permanent sidebar filter with price range slider
  - Mobile: Slide-out drawer filter with swipe-to-close gestures
  - Tablet: Adaptive grid and responsive behaviors

- **Price Range Filter**:
  - Interactive dual-handle slider with smooth animations
  - Direct numeric input fields with validation
  - Real-time visual feedback on price track
  - Auto-submission on desktop, manual apply on mobile

- **Enhanced User Experience**:
  - Loading indicators with subtle animations
  - Skeleton loaders during content transitions
  - Active filter tags with one-click removal
  - Persistent filter state across navigation
  - Keyboard and touch-optimized interactions

- **Technical Implementation**:
  - Blade component (`search-filter.blade.php`) with configurable routes
  - Pure JavaScript for slider interactions (no external libraries)
  - CSS Grid for responsive product layouts
  - Laravel pagination integration with query string preservation

### **Usage:**
The search component is implemented as a reusable Blade component that can be included on any collection page:
```php
@include('components.search-filter', [
    'route' => 'collections', // or 'collections.women', 'collections.men'.
    'products' => $products,
    'searchPlaceholder' => 'Search perfumes...'
])