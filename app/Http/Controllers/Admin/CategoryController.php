<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BdgsCategory;
use App\Models\BdgsDataType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct()
    {
        Gate::authorize('manage-content');
    }

    public function index(string $type): View
    {
        $dataType = BdgsDataType::query()->where('slug', $type)->firstOrFail();
        $categories = BdgsCategory::query()
            ->where('post_type_id', $dataType->id)
            ->with('parent')
            ->orderBy('sort_order')
            ->get();

        return view('admin.categories.index', compact('dataType', 'categories', 'type'));
    }

    public function store(Request $request, string $type): RedirectResponse
    {
        $dataType = BdgsDataType::query()->where('slug', $type)->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200'],
            'parent_id' => ['nullable', 'exists:bdgs_categories,id'],
            'description' => ['nullable', 'string'],
        ]);

        BdgsCategory::query()->create([
            'post_type_id' => $dataType->id,
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'parent_id' => $validated['parent_id'] ?? null,
            'description' => $validated['description'] ?? null,
        ]);

        return back()->with('status', 'Category created.');
    }

    public function destroy(string $type, BdgsCategory $category): RedirectResponse
    {
        $category->delete();

        return back()->with('status', 'Category deleted.');
    }
}
