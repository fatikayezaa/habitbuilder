<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', Auth::id())
            ->withCount('habits')
            ->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'color' => 'required|string|max:20',
            'icon' => 'required|string|max:100',
        ]);

        Category::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'color' => $request->color,
            'icon' => $request->icon,
        ]);

        return redirect()->back()->with('success', 'Kategori berhasil ditambah!');
    }

    public function edit(Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'color' => 'required|string|max:20',
            'icon' => 'required|string|max:100',
        ]);

        $category->update([
            'name' => $request->name,
            'color' => $request->color,
            'icon' => $request->icon,
        ]);

        return redirect('/categories');
    }

    public function destroy(Category $category)
    {
        if ($category->user_id === Auth::id()) {
            $category->delete();
        }

        return redirect()->back();
    }
}