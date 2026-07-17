<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use App\Models\Tag;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index()
    {
        $managers = Manager::withCount('tags')->with('tags')->get();
        return view('managers.index', compact('managers'));
    }

    public function create()
    {
        $tags = Tag::all();
        return view('managers.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|max:255',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $manager = Manager::create(['name' => $request->name]);
        $manager->tags()->sync($request->tag_ids ?? []);

        return redirect()->route('managers.index')->with('success', 'Manager created successfully!');
    }

    public function edit(Manager $manager)
    {
        $tags = Tag::all();
        $manager->load('tags');
        return view('managers.edit', compact('manager', 'tags'));
    }

    public function update(Request $request, Manager $manager)
    {
        $request->validate([
            'name'    => 'required|max:255',
            'tag_ids' => 'nullable|array',
            'tag_ids.*' => 'exists:tags,id',
        ]);

        $manager->update(['name' => $request->name]);
        $manager->tags()->sync($request->tag_ids ?? []);

        return redirect()->route('managers.index')->with('success', 'Manager updated successfully!');
    }

    public function destroy(Manager $manager)
    {
        $manager->delete();
        return redirect()->route('managers.index')->with('success', 'Manager deleted successfully!');
    }
}
