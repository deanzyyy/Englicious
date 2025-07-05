<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    // ADMIN: List all news
    public function index()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Access denied. Admin privileges required.');
        }
        $news = News::with('creator')->orderByDesc('date')->paginate(10);
        $total = News::count();
        return view('admin.news.index', compact('news', 'total'));
    }

    // ADMIN: Show create form
    public function create()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Access denied. Admin privileges required.');
        }
        return view('admin.news.create');
    }

    // ADMIN: Store news
    public function store(Request $request)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Access denied. Admin privileges required.');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'date' => 'required|date',
        ]);
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('news_images', 'public');
        }
        $validated['created_by'] = Auth::id();
        News::create($validated);
        return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
    }

    // ADMIN: Show edit form
    public function edit(News $news)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Access denied. Admin privileges required.');
        }
        return view('admin.news.edit', compact('news'));
    }

    // ADMIN: Update news
    public function update(Request $request, News $news)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Access denied. Admin privileges required.');
        }
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'date' => 'required|date',
        ]);
        if ($request->hasFile('image')) {
            if ($news->image) Storage::disk('public')->delete($news->image);
            $validated['image'] = $request->file('image')->store('news_images', 'public');
        }
        $news->update($validated);
        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }

    // ADMIN: Delete news
    public function destroy(News $news)
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Access denied. Admin privileges required.');
        }
        if ($news->image) Storage::disk('public')->delete($news->image);
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }

    // API: List news for student (JSON)
    public function list()
    {
        $news = News::orderByDesc('date')->get();
        return response()->json($news);
    }

    // API: Like news
    public function like($id)
    {
        $news = News::findOrFail($id);
        $user = Auth::user();
        // Cek apakah user sudah like
        if ($news->likedUsers()->where('user_id', $user->id)->exists()) {
            // Sudah like, kembalikan jumlah like tanpa menambah
            return response()->json(['likes' => $news->likes]);
        }
        // Tambahkan ke pivot table
        $news->likedUsers()->attach($user->id);
        // Update field likes
        $news->increment('likes');
        return response()->json(['likes' => $news->likes]);
    }

    // API: Increment view
    public function incrementView($id)
    {
        $news = News::findOrFail($id);
        $news->increment('views');
        return response()->json(['views' => $news->views]);
    }
} 