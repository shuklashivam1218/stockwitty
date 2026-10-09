<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogCategoriesController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount('posts')->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.blog.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateCategory($request);
        BlogCategory::create($data + ['slug' => $this->uniqueSlug($data['name'])]);

        return back()->with('success', 'Category added.');
    }

    public function update(Request $request, int $id)
    {
        $category = BlogCategory::findOrFail($id);
        $category->update($this->validateCategory($request, $category->id));

        return back()->with('success', 'Category updated.');
    }

    /** Only empty categories can go; one with posts should be switched off instead. */
    public function destroy(int $id)
    {
        $category = BlogCategory::withCount(['posts' => fn ($q) => $q->withTrashed()])->findOrFail($id);

        if ($category->posts_count > 0) {
            return back()->with('error', "\"{$category->name}\" still has posts. Move them or mark the category inactive instead.");
        }

        $category->delete();

        return back()->with('success', 'Category deleted.');
    }

    private function validateCategory(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:80', Rule::unique('blog_categories', 'name')->ignore($ignoreId)],
            'sort_order' => 'nullable|integer|min:0|max:999',
        ]);

        $data['name']       = trim(strip_tags($data['name']));
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['is_active']  = $request->boolean('is_active');

        return $data;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug(str_replace('&', ' ', $name)) ?: 'category';
        $slug = $base;
        $n    = 2;
        while (BlogCategory::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $n++;
        }

        return $slug;
    }
}
