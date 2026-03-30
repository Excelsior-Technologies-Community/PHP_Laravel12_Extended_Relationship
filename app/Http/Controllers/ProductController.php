<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Manager;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('managers')->get();
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
}