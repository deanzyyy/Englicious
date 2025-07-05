@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            
            <h2 class="mt-4 text-xl font-bold text-gray-900">
                Error Saat Memproses PDF
            </h2>
            
            <p class="mt-2 text-gray-600">
                {{ $message }}
            </p>
            
            <div class="mt-6">
                <button onclick="window.history.back()" 
                        class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    Kembali
                </button>
            </div>
        </div>
    </div>
</div>
@endsection 