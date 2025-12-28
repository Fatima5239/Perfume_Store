{{-- resources/views/shop/items.blade.php --}}
@extends('layouts.app')

@section('title', 'Gift Items & Packages | PERFUME AL WISSAM')

@section('content')
    @include('components.search-filter', [
        'route' => 'collections.gifts',
        'searchPlaceholder' => 'Search gift items...',
        'products' => $products,
        'isItemsPage' => true,  
        'showDescription' => true 
    ])
@endsection