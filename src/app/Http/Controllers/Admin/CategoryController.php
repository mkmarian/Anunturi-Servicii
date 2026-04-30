<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Shared\Models\ServiceCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::withCount('listings')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = ServiceCategory::whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:120'],
            'icon'       => ['nullable', 'string', 'max:80'],
            'parent_id'  => ['nullable', 'exists:service_categories,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['nullable', 'boolean'],
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        // Ensure slug uniqueness
        $base = $data['slug'];
        $i = 1;
        while (ServiceCategory::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $base . '-' . $i++;
        }

        ServiceCategory::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', "Categoria \"{$data['name']}\" a fost creată.");
    }

    public function edit(ServiceCategory $category)
    {
        $parents = ServiceCategory::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, ServiceCategory $category)
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:120'],
            'icon'       => ['nullable', 'string', 'max:80'],
            'parent_id'  => ['nullable', 'exists:service_categories,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active'  => ['nullable', 'boolean'],
        ]);

        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? 0;

        // Regenerate slug only if name changed
        if ($data['name'] !== $category->name) {
            $base = Str::slug($data['name']);
            $slug = $base;
            $i = 1;
            while (ServiceCategory::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $data['slug'] = $slug;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', "Categoria \"{$category->name}\" a fost actualizată.");
    }

    public function destroy(ServiceCategory $category)
    {
        if ($category->listings()->count() > 0) {
            return back()->with('error', "Nu poți șterge categoria \"{$category->name}\" — are anunțuri asociate.");
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Categoria \"{$name}\" a fost ștearsă.");
    }
}
