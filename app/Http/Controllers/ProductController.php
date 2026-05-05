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
            $query->where('name', 'like', "%$search%");
        })
        ->paginate(6);

    return view('products.index', compact('products'));
}
    // Show create form
    public function create()
    {
        $managers = Manager::all(); // list of managers for dropdown
        return view('products.create', compact('managers'));
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'created_by' => 'nullable|exists:managers,id',
            'updated_by' => 'nullable|exists:managers,id',
            'deleted_by' => 'nullable|exists:managers,id',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }
    // Edit form
public function edit($id)
{
    $product = Product::findOrFail($id);
    $managers = Manager::all();

    return view('products.edit', compact('product', 'managers'));
}

// Update
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'created_by' => 'nullable|exists:managers,id',
        'updated_by' => 'nullable|exists:managers,id',
        'deleted_by' => 'nullable|exists:managers,id',
    ]);

    $product = Product::findOrFail($id);
    $product->update($request->all());

    return redirect()->route('products.index')->with('success', 'Product updated!');
}

// Delete
public function destroy($id)
{
    Product::findOrFail($id)->delete();
    return back()->with('success', 'Product deleted!');
}
}