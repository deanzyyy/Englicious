@extends('layouts.app')

@section('content')
<div class="w-full px-0 py-6">
    <h1 class="text-2xl font-bold mb-6 text-white">Add News</h1>
    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 w-full">
        @csrf
        <div>
            <label class="block font-semibold mb-1 text-white">Title</label>
            <input type="text" name="title" class="w-full bg-[#18161d] text-white rounded-xl px-6 py-4 focus:ring-pink-500 focus:border-pink-500 outline-none" required value="{{ old('title') }}">
            @error('title')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block font-semibold mb-1 text-white">Image</label>
            <input type="file" name="image" class="w-full bg-[#18161d] text-white rounded-xl px-6 py-4 focus:ring-pink-500 focus:border-pink-500 outline-none">
            @error('image')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block font-semibold mb-1 text-white">Description</label>
            <textarea name="description" class="w-full bg-[#18161d] text-white rounded-xl px-6 py-4 focus:ring-pink-500 focus:border-pink-500 outline-none" rows="4" required>{{ old('description') }}</textarea>
            @error('description')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="block font-semibold mb-1 text-white">Date</label>
            <input type="date" name="date" class="w-full bg-[#18161d] text-white rounded-xl px-6 py-4 focus:ring-pink-500 focus:border-pink-500 outline-none" required value="{{ old('date') }}">
            @error('date')<div class="text-red-400 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="flex justify-end gap-4 w-full">
            <a href="{{ route('admin.news.index') }}" class="border border-pink-500 text-pink-500 px-8 py-3 rounded-xl font-semibold hover:bg-pink-500/10 transition">Cancel</a>
            <button type="submit" class="px-8 py-3 rounded-xl text-white font-semibold bg-pink-500 hover:from-pink-600 hover:to-orange-600 transition">Save</button>
        </div>
    </form>
</div>
@endsection 