<?php

// app/Http/Controllers/CategoryController.php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where(function ($query) {
            $query->whereNull('user_id') // globais
                  ->orWhere('user_id', Auth::id()); // pessoais
        })->orderBy('name')->get();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'is_global' => 'boolean',
        ]);

        $data['user_id'] = Auth::id();

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $data = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Categoria atualizada!');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Categoria removida.');
    }
}

