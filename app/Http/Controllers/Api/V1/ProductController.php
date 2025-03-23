<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProductStoreRequest;
use App\Http\Requests\V1\ProductUpdateRequest;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use App\Traits\StorageFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    use ApiResponse, StorageFile, LoadRelations;

    // tên quan hệ hợp lệ (check trên url)
    protected $validRelations = [
        'category',
        'variants',
        'variants.productImages',
        'variants.variantValues',
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
            'products' => ProductResource::collection($products->get()),
        ]);
    }

    public function store(ProductStoreRequest $request)
    {
        $validatedData = $request->toArray();

        // Xử lí upload file cho thumbnail của product
        if ($request->hasFile('thumbnail')) {
            $validatedData['thumbnail'] = $request->file('thumbnail')->store('product_thumbnails');
        }

        $product = Product::create($validatedData);

        // Xử lí upload file cho bảng product image
        $images = [];
        if ($request->hasFile('productImages')) {
            foreach ($request->file('productImages') as $file) {
                $images[] = ['image_url' => $file->store('product_images')];
            }
        }

        $product->productImages()->createMany($images);

        $this->loadRelations($product, $request, true);

        return $this->created("Tạo sản phẩm thành công", [
            'product' => new ProductResource($product),
        ]);
    }

    public function show(string $slug, Request $request)
    {
        $product = Product::whereSlug($slug)->first();

        if (!$product) return $this->not_found('Sản phẩm không tồn tại');

        $this->loadRelations($product, $request, true);

        return $this->ok('Lấy chi tiết sản phẩm thành công', [
            'product' => new ProductResource($product),
        ]);
    }

    public function update(ProductUpdateRequest $request, string $slug)
    {
        $product = Product::whereSlug($slug)->first();

        if (!$product) return $this->not_found("Sản phẩm không tồn tại");

        $validatedData = $request->toArray();

        // Xử lí upload file cho thumbnail của product
        if ($request->hasFile('thumbnail')) {

            // Xóa thumbnail hiện tại trên storage
            $this->delete_storage_file($product, 'thumbnail');

            // upload thumbnail mới lên storage
            $validatedData['thumbnail'] = $request->file('thumbnail')->store('product_thumbnails');
        }

        $product->update($validatedData);

        // Xử lí upload file cho bảng product image
        $images = [];
        if ($request->hasFile('productImages')) {

            // Xóa ảnh cũ trong db vàstorage 
            $this->delete_storage_product_images($product);

            // upload ảnh mới lên storage
            foreach ($request->file('productImages') as $file) {
                $images[] = ['image_url' => $file->store('product_images')];
            }
        }

        $product->productImages()->createMany($images);

        $this->loadRelations($product, $request, true);

        return $this->ok("Cập nhật thành công", [
            'product' => new ProductResource($product),
        ]);
    }

    public function destroy(string $slug)
    {
        $product = Product::whereSlug($slug)->first();

        if (!$product) return $this->not_found("Sản phẩm không tồn tại");

        $this->delete_storage_file($product, 'thumbnail');

        $this->delete_storage_product_images($product);

        $product->delete();
        // ⬆️ đồng thời xóa luôn variants, productImages, reviews

        return $this->no_content();
    }

    public function update_single_product_image(Request $request, string $slug, $id)
    {
        $product = Product::whereSlug($slug)->first();

        if (!$product) return $this->not_found('Sản phẩm không tồn tại');

        $image = $product->productImages()->find($id);

        if (!$image) return $this->not_found('Ảnh không tồn tại hoặc không thuộc sản phẩm này');

        // validate
        $validatedData = $request->validate(
            [
                'imageUrl' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
            ],
            [
                'imageUrl.required' => 'Vui lòng tải lên ít nhất 1 ảnh sản phẩm',
                'imageUrl.image'    => 'Ảnh sản phẩm không hợp lệ',
                'imageUrl.max'      => 'Vui lòng chọn ảnh sản phẩm có kích thước < :max',
                'imageUrl.mimes'    => 'Ảnh phải là tệp có định dạng: :values',
            ]
        );

        if ($request->hasFile('imageUrl')) {
            // xóa file trên storage
            $this->delete_storage_file($image, 'image_url');
            // upload ảnh mới lên storage
            $validatedData['imageUrl'] = $request->file('imageUrl')->store('product_images');
        }
        // thêm path vào db
        $image->update($validatedData);

        $this->loadRelations($product, $request, true);

        return $this->ok("Cập nhật thành công", [
            'product' => new ProductResource($product),
        ]);
    }

    public function delete_single_product_image(string $slug, $id)
    {
        $product = Product::whereSlug($slug)->first();

        if (!$product) return $this->not_found('Sản phẩm không tồn tại');

        $image = $product->productImages()->find($id);

        if (!$image) return $this->not_found('Ảnh không tồn tại hoặc không thuộc sản phẩm này');

        $this->delete_storage_file($image,'image_url');

        $image->delete();
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

    protected function delete_storage_product_images(Product $product)
    {
        $product->loadMissing('productImages');

        foreach ($product->productImages as $image) {

            $this->delete_storage_file($image, 'image_url');

            $image->delete();
        }

        $product->unsetRelation('productImages');
    }
}
