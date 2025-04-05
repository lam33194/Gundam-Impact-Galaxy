<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryService
{
    public function all($prePage = 10)
    {
        return Category::query()
            ->latest('id')
            ->where('is_active', true)
            ->paginate($prePage);
    }

    public function store(array $data)
    {
        $data['status'] ??= 0;
        $data['is_active'] ??= 0;
        $data['slug'] = Str::slug($data['name'], '-') . '-' .  Str::ulid();

        $category =  Category::create($data);
        return $category;
    }

    public function update($category, $data)
    {
        $data['status'] = !isset($data['status']) ? 0 : 1;
        $data['is_active'] = !isset($data['is_active']) ? 0 : 1;
        $data['slug'] = Str::slug($data['name'], '-') . '-' .  Str::ulid();

        $category->update($data);

        return $category;
    }
}
