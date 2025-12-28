{{-- resources/views/shop/category.blade.php --}}
@extends('layouts.app')

@section('title', htmlspecialchars($title) . ' | PERFUME AL WISSAM')

@section('content')
    @include('components.search-filter', [
        'route' => 'collections.' . $gender,  // 'collections.women', 'collections.men', etc.
        'searchPlaceholder' => 'Search ' . $gender . ' perfumes...',
        'products' => $products
    ])
@endsection