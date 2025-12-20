{{-- resources/views/shop/collections.blade.php --}}
@extends('layouts.app')

@section('title', 'All Collections | PERFUME AL WISSAM')

@section('content')
    @include('components.search-filter', [
        'route' => 'collections',
        'searchPlaceholder' => 'Search perfumes...',
        'products' => $products
    ])
@endsection