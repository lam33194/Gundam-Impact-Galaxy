<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductGallery;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use App\Models\Tag;
use App\Traits\StorageFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use StorageFile;

    public function index(Request $request)
    {
        $query = Product::query();

        // Apply search filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('id', $search);
            });
        }

        // Apply boolean filters
        $booleanFilters = ['is_active', 'is_hot_deal', 'is_good_deal', 'is_new', 'is_show_home'];

        foreach ($booleanFilters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, 1);
            }
        }

        $products = $query->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $colors = ProductColor::all();
        $sizes = ProductSize::all();
        $tags = Tag::all();
        return view('admin.products.create', compact(['categories', 'tags', 'colors', 'sizes', 'tags']));
    }

    public function show(Product $product)
    {
        $product->load(['variants', 'galleries', 'tags', 'category']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $colors = ProductColor::all();
        $sizes = ProductSize::all();
        $tags = Tag::all();

        $product->loadMissing(['galleries', 'tags', 'variants', 'category', 'variants.color', 'variants.size']);
        return view('admin.products.edit', compact(['product', 'categories', 'tags', 'colors', 'sizes']));
    }

    public function store(ProductStoreRequest $request)
    {
        // dd($request->all());

        [$dataProduct, $dataProductVariants, $dataProductTags, $dataProductGalleries] = $this->handleData($request);

        try {
            DB::beginTransaction();

            $product = Product::query()->create($dataProduct);

            foreach ($dataProductVariants as $item) {
                $item += ['product_id' => $product->id];
                ProductVariant::query()->create($item);
            }

            $product->tags()->attach($dataProductTags);

            foreach ($dataProductGalleries as $item) {
                $item += ['product_id' => $product->id];
                ProductGallery::create($item);
            }

            DB::commit();

            return redirect()->route('admin.products.index')->with('success', 'Tạo sản phẩm thành công');
        } catch (\Exception $e) {
            DB::rollBack();

            if (!empty($dataProduct['thumb_image']) && Storage::exists($dataProduct['thumb_image'])) {
                Storage::delete($dataProduct['thumb_image']);
            }
            ;

            foreach (array_merge($dataProductVariants, $dataProductGalleries) as $item) {
                if (!empty($item['image']) && Storage::exists($item['image'])) {
                    Storage::delete($item['image']);
                }
            }

            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function handleData(Request $request)
    {
        $dataProduct = $request->product;

        $dataProduct['is_active'] ??= 0;
        $dataProduct['is_hot_deal'] ??= 0;
        $dataProduct['is_good_deal'] ??= 0;
        $dataProduct['is_new'] ??= 0;
        $dataProduct['is_show_home'] ??= 0;
        $dataProduct['slug'] = Str::slug($dataProduct['name']) . '-' . Str::ulid();
        $dataProduct['price_sale'] ??= 0;

        if ($request->hasFile('product.thumb_image')) {
            $dataProduct['thumb_image'] = Storage::put('products', $dataProduct['thumb_image']);
        }

        $dataProductVariantsTmp = $request->product_variants;
        $dataProductVariants = [];

        foreach ($dataProductVariantsTmp as $key => $item) {
            $tmp = explode('-', $key);

            $dataProductVariants[] = [
                'product_color_id' => $tmp[0],
                'product_size_id' => $tmp[1],
                'quantity' => $item['quantity'],
                'image' => !empty($item['image']) ? Storage::put('product_variants', $item['image']) : null
            ];
        }


        // handle product galleries

        $dataProductGalleriesTmp = $request->product_galleries ?: [];
        $dataProductGalleries = [];

        foreach ($dataProductGalleriesTmp as $image) {
            if (!empty($image)) {
                $dataProductGalleries[] = [
                    'image' => Storage::put('product_galleries', $image)
                ];
            }
        }


        // end product galleries

        $dataProductTags = $request->tags;

        return [$dataProduct, $dataProductVariants, $dataProductTags, $dataProductGalleries];
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        [$dataProduct, $dataProductVariants, $dataProductTags, $dataProductGalleries] = $this->handleUpdateData($request, $product);

        try {
            DB::beginTransaction();

            // Store old image paths for cleanup if needed
            $oldThumbImage = $product->thumb_image;
            $oldVariantImages = $product->variants->pluck('image')->toArray();
            $oldGalleryImages = $product->galleries->pluck('image')->toArray();

            // Update product
            $product->update($dataProduct);

            // Only update variants if new data is provided
            if (!empty($dataProductVariants)) {
                $product->variants()->delete();
                foreach ($dataProductVariants as $item) {
                    $item += ['product_id' => $product->id];
                    ProductVariant::query()->create($item);
                }
            }

            // Sync tags
            $product->tags()->sync($dataProductTags);

            // Only update galleries if new images are provided
            if (!empty($dataProductGalleries)) {
                $product->galleries()->delete();
                foreach ($dataProductGalleries as $item) {
                    $item += ['product_id' => $product->id];
                    ProductGallery::create($item);
                }
            }

            DB::commit();

            // Clean up old thumb image if it was replaced
            if ($oldThumbImage && $oldThumbImage !== $dataProduct['thumb_image'] && Storage::exists($oldThumbImage)) {
                Storage::delete($oldThumbImage);
            }

            return redirect()->back()->with('success', 'Cập nhật sản phẩm thành công');
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up new uploaded images on failure
            if (!empty($dataProduct['thumb_image']) && $dataProduct['thumb_image'] !== $oldThumbImage && Storage::exists($dataProduct['thumb_image'])) {
                Storage::delete($dataProduct['thumb_image']);
            }

            foreach (array_merge($dataProductVariants, $dataProductGalleries) as $item) {
                if (!empty($item['image']) && Storage::exists($item['image'])) {
                    Storage::delete($item['image']);
                }
            }

            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function handleUpdateData(Request $request, Product $product)
    {
        $dataProduct = $request->product;

        // Set default values
        $dataProduct['is_active'] ??= 0;
        $dataProduct['is_hot_deal'] ??= 0;
        $dataProduct['is_good_deal'] ??= 0;
        $dataProduct['is_new'] ??= 0;
        $dataProduct['is_show_home'] ??= 0;
        $dataProduct['price_sale'] ??= 0;

        // Only update slug if name changed
        if ($dataProduct['name'] !== $product->name) {
            $dataProduct['slug'] = Str::slug($dataProduct['name']) . '-' . Str::ulid();
        }

        // Handle thumbnail image
        if ($request->hasFile('product.thumb_image')) {
            $dataProduct['thumb_image'] = Storage::put('products', $dataProduct['thumb_image']);
        } else {
            $dataProduct['thumb_image'] = $product->thumb_image; // Keep existing if no new upload
        }

        // Handle product variants
        $dataProductVariantsTmp = $request->product_variants ?? [];
        $dataProductVariants = [];

        foreach ($dataProductVariantsTmp as $key => $item) {
            $tmp = explode('-', $key);
            $dataProductVariants[] = [
                'product_color_id' => $tmp[0],
                'product_size_id' => $tmp[1],
                'quantity' => $item['quantity'],
                'image' => !empty($item['image']) && $request->hasFile("product_variants.{$key}.image")
                    ? Storage::put('product_variants', $item['image'])
                    : ($product->variants->where('product_color_id', $tmp[0])->where('product_size_id', $tmp[1])->first()?->image ?? null)
            ];
        }

        // Handle product galleries
        $dataProductGalleriesTmp = $request->product_galleries ?: [];
        $dataProductGalleries = [];

        foreach ($dataProductGalleriesTmp as $key => $image) {
            if (!empty($image) && $request->hasFile("product_galleries.{$key}")) {
                $dataProductGalleries[] = [
                    'image' => Storage::put('product_galleries', $image)
                ];
            }
        }

        $dataProductTags = $request->tags;

        return [$dataProduct, $dataProductVariants, $dataProductTags, $dataProductGalleries];
    }

    public function destroy(Product $product)
    {
        $product->load('galleries');

        foreach ($product->galleries as $gallery) {
            $this->delete_storage_file($gallery, 'image');
            $gallery->delete();
        }

        $product->delete();

        return redirect()->back()->with('success', 'Xóa sản phẩm thành công');
    }
}
