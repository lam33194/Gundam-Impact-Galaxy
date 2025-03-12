<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProductStoreRequest;
use App\Http\Requests\V1\ProductUpdateRequest;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use ApiResponse;

    // tên quan hệ hợp lệ (check trên url)
    protected $validRelations = [
        'category',
        'category.parent',
        'category.children',
        'variants',
        'productImages',
        'reviews',
        'reviews.user'
    ];

    // nháp
    protected $validFilterFields = [
        'name',
        'slug',
        'price',
    ];

    public function index(Request $request)
    {
        $products = Product::query();

        $this->loadRelations($products, $request);

        $this->applyFilters($products, $request);

        return $this->ok('Lấy danh sách sản phẩm thành công', [
            'products' => ProductResource::collection($products->paginate(10)),
        ]);
    }

    public function store(ProductStoreRequest $request)
    {
        $validatedData = $request->validated();

        // Xử lí upload file cho thumbnail của product
        if ($request->hasFile('thumbnail')) {
            $validatedData['thumbnail'] = $request->file('thumbnail')->store('product_thumbnails');
        }
        $product = Product::create($validatedData);

        // Xử lí upload file cho bảng product image
        $images = [];
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $file) {
                $images[] = ['image_url' => $file->store('product_images')];
            }
        }

        $product->productImages()->createMany($images);

        // Tùy
        $product->load('productImages');

        return $this->created("Tạo sản phẩm thành công", [
            'user' => new ProductResource($product),
        ]);
    }

    public function show(string $slug, Request $request)
    {
        $product = Product::whereSlug($slug)->first();

        if (!$product) return $this->not_found('Sản phẩm không tồn tại');

        $this->loadRelations($product, $request, true);

        return $this->ok('Lấy danh sách sản phẩm thành công', [
            'product' => new ProductResource($product),
        ]);
    }

    public function update(ProductUpdateRequest $request, string $slug)
    {
        $product = Product::with('productImages')->whereSlug($slug)->first();

        if (!$product) return $this->not_found("Sản phẩm không tồn tại");

        $validatedData = $request->validated();

        // Xử lí upload file cho thumbnail của product
        if ($request->hasFile('thumbnail')) {

            // Xóa thumbnail hiện tại trên storage
            $this->deleteStorageThumbnail($product);

            // upload thumbnail mới lên storage
            $validatedData['thumbnail'] = $request->file('thumbnail')->store('product_thumbnails');
        }

        $product->update($validatedData);

        // Xử lí upload file cho bảng product image
        $images = [];
        if ($request->hasFile('product_images')) {

            // Xóa ảnh cũ trong db vàstorage 
            $this->deleteStorageProductImage($product);

            // upload ảnh mới lên storage
            foreach ($request->file('product_images') as $file) {
                $images[] = ['image_url' => $file->store('product_images')];
            }
        }

        $product->productImages()->createMany($images);

        $product->load('productImages');

        return $this->ok("Cập nhật thành công", [
            'product' => new ProductResource($product),
        ]);
    }

    public function destroy(string $slug)
    {
        $product = Product::whereSlug($slug)->first();

        if (!$product) return $this->not_found("Sản phẩm không tồn tại");

        $this->deleteStorageThumbnail($product);

        $this->deleteStorageProductImage($product);
        
        $product->delete();
        // ⬆️ đồng thời xóa luôn variants, productImages, reviews
        
        return $this->no_content();
    }

    private function loadRelations($query, Request $request, $loadMissing = false)
    {
        // eager load relations nếu có param 'include' trên url
        if ($request->has('include')) {

            // gộp chuỗi thành mảng (vd: ?include=reviews,abc => ['reviews','abc'])
            $queryRelations = explode(',', $request->query('include'));

            foreach ($queryRelations as $relation) {
                // nếu nhập tên quan hệ không hợp lệ, chuyển đến vòng lặp tiếp theo
                if (!in_array($relation, $this->validRelations))
                    continue;

                if ($loadMissing) {
                    $query->loadMissing($relation);
                } else {
                    // eager load 
                    $query->with($relation);
                }
            }
        }
    }

    private function applyFilters($query, Request $request)
    {
        // Tìm kiếm theo tên
        if ($request->has('name')) {
            $query->nameFilter($request->query('name'));
        }

        // Tìm kiếm theo slug
        if ($request->has('slug')) {
            $query->slugFilter($request->query('slug'));
        }

        // Lọc theo giá (default 0 -> 999999999)
        if ($request->has(['minPrice', 'maxPrice'])) {
            $query->priceFilter($request->query('minPrice', 0), $request->query('maxPrice', 999999999));
        }
    }

    protected function deleteStorageThumbnail(Product $product): void
    {
        $path = $product->thumbnail;

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    protected function deleteStorageProductImage(Product $product)
    {
        foreach ($product->productImages as $image) {

            $path = $image->image_url;

            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            $image->delete();
        }
    }
}
