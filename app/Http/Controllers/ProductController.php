<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Manager;
use App\Models\Tag;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->search;
        $tagFilter = $request->tag_id;

        $products = Product::with(['managers', 'tags'])
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($tagFilter, fn($q) => $q->whereHas('tags', fn($q2) => $q2->where('tags.id', $tagFilter)))
            ->paginate(3);

        $totalProducts  = Product::count();
        $activeProducts = Product::where('status', 'Active')->count();
        $totalManagers  = Manager::count();
        $allTags        = Tag::all();

        return view('products.index', compact(
            'products', 'totalProducts', 'activeProducts', 'totalManagers', 'allTags'
        ));
    }

    public function create()
    {
        $managers = Manager::all();
        $tags     = Tag::all();
        return view('products.create', compact('managers', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|max:255',
            'description' => 'nullable',
            'created_by'  => 'nullable|exists:managers,id',
            'updated_by'  => 'nullable|exists:managers,id',
            'deleted_by'  => 'nullable|exists:managers,id',
            'status'      => 'required',
            'tag_ids'     => 'nullable|array',
            'tag_ids.*'   => 'exists:tags,id',
        ]);

        $product = Product::create($request->except('tag_ids'));
        $product->tags()->sync($request->tag_ids ?? []);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $product->load(['managers', 'tags']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $managers = Manager::all();
        $tags     = Tag::all();
        $product->load('tags');
        return view('products.edit', compact('product', 'managers', 'tags'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|max:255',
            'description' => 'nullable',
            'created_by'  => 'nullable|exists:managers,id',
            'updated_by'  => 'nullable|exists:managers,id',
            'deleted_by'  => 'nullable|exists:managers,id',
            'status'      => 'required',
            'tag_ids'     => 'nullable|array',
            'tag_ids.*'   => 'exists:tags,id',
        ]);

        $product->update($request->except('tag_ids'));
        $product->tags()->sync($request->tag_ids ?? []);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully!');
    }
}
