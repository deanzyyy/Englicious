@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-dark-lighter rounded-lg shadow-xl p-6 min-h-screen border border-gray-800">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-200">{{ $title }}</h1>
            <button onclick="window.history.back()" 
                    class="text-pink hover:text-pink-dark transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <div class="pdf-content">
            @foreach($content as $paragraph)
                <p class="text-gray-300">
                    {!! nl2br(e($paragraph)) !!}
                </p>
            @endforeach
        </div>
    </div>
</div>

<style>
    .pdf-content {
        font-size: 16px;
        line-height: 1.8;
        max-width: 900px;
        margin: 0 auto;
    }
    
    .pdf-content p {
        margin-bottom: 1.5rem;
        text-align: justify;
        hyphens: auto;
    }
    
    /* Custom scrollbar for Webkit browsers */
    ::-webkit-scrollbar {
        width: 12px;
    }
    
    ::-webkit-scrollbar-track {
        background: #1a1a1a;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #FF1493;
        border-radius: 6px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #C71585;
    }
    
    @media (max-width: 768px) {
        .pdf-content {
            font-size: 14px;
            line-height: 1.6;
        }
    }
    
    @media print {
        body {
            background: white;
            color: black;
        }
        
        .pdf-content {
            font-size: 12pt;
        }
        
        .pdf-content p {
            color: black;
        }
        
        button {
            display: none;
        }
    }
</style>
@endsection 