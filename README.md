# Perfume Al Wissam

A modern, fully-featured e-commerce store built with Laravel for selling premium perfumes and fragrances.

## 🚀 Features

- **Full Product Management**: Create, update, delete, and categorize perfumes (men's, women's, unisex).
- **Advanced Shopping Experience**: Search filters, product views, related items, and detailed product pages.
- **Real-time Admin Analytics**: Track visitor stats, popular searches, and product views with a dedicated dashboard.
- **Secure Admin Panel**: Protected product management with authentication and authorization.

## 🛠️ Technology Stack

- **Backend**: Laravel 12.x[citation:2]
- **Frontend**: Laravel Blade, Tailwind CSS
- **Database**: MySQL (with migrations for products, brands, analytics, etc.)
- **Authentication**: Laravel's built-in auth system

## 📁 Key Project Components

- **Models**: `Product`, `Brand`, `Fragrance`, `VisitorStat`, `SearchStat`, `ProductView`
- **Controllers**: `HomeController`, `ShopController`, `AdminDashboardController`, `ProductController`
- **Services**: `TrackingService` (for analytics)
- **Migrations**: Database schemas for all core features

## 🔧 Installation

1.  **Clone the repository:**
    ```bash
    git clone [your-repository-url]
    cd perfume-al-wissam
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    npm install && npm run build
    ```

3.  **Configure environment:**
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
    Update `.env` with your database credentials.

4.  **Run migrations:**
    ```bash
    php artisan migrate
    ```

5.  **Start the development server:**
    ```bash
    php artisan serve
    ```

6.  **Access the application:**
    - Shop: `http://localhost:8000`
    - Admin: `http://localhost:8000/admin/dashboard`

## 👨‍💼 Admin Access

The admin panel (`/admin`) is protected by middleware[citation:6]. Ensure you have a user account and are logged in to access product management and the analytics dashboard.

## 🔒 Security Considerations

- **Content Security Policy (CSP)**: Consider implementing a CSP to control which external resources can be loaded, protecting against certain types of attacks like XSS[citation:3][citation:5]. The `spatie/laravel-csp` package can help with this[citation:3].
- **Stay Updated**: Always keep Laravel and its dependencies updated to the latest versions to receive security fixes[citation:1].

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).