<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Manager;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $products = Product::with('managers')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->paginate(3);

        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'Active')->count();
        $totalManagers = Manager::count();

        return view('products.index', compact(
            'products',
            'totalProducts',
            'activeProducts',
            'totalManagers'
        ));
    }

    public function create()
    {
        $managers = Manager::all();

        return view('products.create', compact('managers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'created_by' => 'nullable|exists:managers,id',
            'updated_by' => 'nullable|exists:managers,id',
            'deleted_by' => 'nullable|exists:managers,id',
            'status' => 'required'
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $managers = Manager::all();

        return view('products.edit', compact('product', 'managers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
            'created_by' => 'nullable|exists:managers,id',
            'updated_by' => 'nullable|exists:managers,id',
            'deleted_by' => 'nullable|exists:managers,id',
            'status' => 'required'
        ]);

        $product = Product::findOrFail($id);

        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', 'Product deleted successfully');
    }
}
