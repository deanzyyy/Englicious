@extends('layouts.app')

@section('content')
<div class="container mx-auto px-8 py-6 max-w-[1200px]">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">News Management</h1>
        <a href="{{ route('admin.news.create') }}" class="bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600">+ Add News</a>
    </div>
    <div class="mb-4 text-gray-300">Total News: <span class="font-bold text-pink-400">{{ $total }}</span></div>
    @if(session('success'))
        <div class="bg-green-600 text-white px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($news as $item)
        <div class="bg-[#211F27] rounded-xl shadow-lg border border-pink-500/20 p-5 flex flex-col h-full">
            @if($item->image)
                <img src="{{ asset('storage/'.$item->image) }}" alt="" class="w-full h-40 object-cover rounded-lg mb-4 border border-pink-400/30">
            @else
                <div class="w-full h-40 flex items-center justify-center bg-pink-500/10 text-pink-400 rounded-lg mb-4">No Image</div>
            @endif
            <h2 class="text-lg font-bold text-white mb-2">{{ $item->title }}</h2>
            <p class="text-gray-300 text-sm mb-2 line-clamp-3">{{ $item->description }}</p>
            <div class="flex items-center text-xs text-gray-400 mb-2">
                <i class="fi fi-rr-calendar mr-1"></i> {{ Carbon\Carbon::parse($item->date)->format('d M Y') }}
            </div>
            <div class="flex items-center gap-4 text-pink-400 mb-4">
                <span><i class="fi fi-rr-heart mr-1"></i> {{ $item->likes }}</span>
                <span><i class="fi fi-rr-eye mr-1"></i> {{ $item->views }}</span>
                <span class="ml-auto text-xs text-gray-400">By: {{ $item->creator->name ?? '-' }}</span>
            </div>
            <div class="flex gap-2 mt-auto">
                <a href="{{ route('admin.news.edit', $item->id) }}" class="flex-1 bg-gradient-to-r from-pink-500 to-orange-500 text-white py-2 rounded-lg text-center font-semibold hover:from-pink-600 hover:to-orange-600 transition">Edit</a>
                <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full border border-pink-500 text-pink-500 py-2 rounded-lg font-semibold bg-transparent hover:bg-pink-500/10 transition" onclick="return confirm('Delete this news?')">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-8">{{ $news->links() }}</div>
</div>
@endsection 