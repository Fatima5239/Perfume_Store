@extends('layouts.app')

@section('title', 'Error | PERFUME AL WISSAM')

@push('styles')
<style>
    .error-container {
        max-width: 600px;
        margin: 100px auto;
        padding: 40px 20px;
        text-align: center;
    }
    
    .error-icon {
        font-size: 48px;
        margin-bottom: 20px;
        color: #ff6b6b;
    }
    
    .error-title {
        font-size: 24px;
        margin-bottom: 15px;
        color: #333;
    }
    
    .error-message {
        color: #666;
        margin-bottom: 30px;
        line-height: 1.6;
    }
    
    .error-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .btn-primary {
        padding: 12px 24px;
        background: #000;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 500;
        transition: background 0.3s;
    }
    
    .btn-primary:hover {
        background: #333;
    }
    
    .btn-secondary {
        padding: 12px 24px;
        background: #f0f0f0;
        color: #333;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 500;
        transition: background 0.3s;
    }
    
    .btn-secondary:hover {
        background: #e0e0e0;
    }
</style>
@endpush

@section('content')
<div class="error-container">
    <div class="error-icon">⚠️</div>
    <h1 class="error-title">Something Went Wrong</h1>
    
    <div class="error-message">
        {{ $message ?? 'We encountered an error while processing your request.' }}
    </div>
    
    <div class="error-actions">
        <a href="{{ route('home') }}" class="btn-primary">Go to Homepage</a>
        <a href="{{ route('collections') }}" class="btn-secondary">Browse Collections</a>
        <a href="javascript:history.back()" class="btn-secondary">Go Back</a>
    </div>
</div>
@endsection