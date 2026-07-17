<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount(['products', 'managers'])->get();
        return view('tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|unique:tags,name|max:100',
            'color'    => 'required',
            'category' => 'nullable|max:100',
        ]);

        Tag::create($request->only('name', 'color', 'category'));
        return redirect()->route('tags.index')->with('success', 'Tag created!');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('tags.index')->with('success', 'Tag deleted!');
    }
}
