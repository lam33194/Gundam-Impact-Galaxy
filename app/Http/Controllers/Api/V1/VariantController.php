<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\VariantStoreRequest;
use App\Http\Requests\V1\VariantUpdateRequest;
use App\Http\Resources\V1\VariantResource;
use App\Models\Variant;
use App\Models\Product;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use App\Traits\StorageFile;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    use ApiResponse, LoadRelations, StorageFile;

    protected $validRelations = [
        'product',
        'productImages',
        'variantValues',
    ];

    public function index(Request $request, string $slug)
    {
        $product = Product::whereSlug($slug)->first();

        if (!$product) return $this->not_found('Sản phẩm không tồn tại');

        $variants = $product->variants;

        $this->loadRelations($variants, $request, true);

        return $this->ok("Lấy danh sách biến thể thành công", [
            'variants' => VariantResource::collection($variants),
        ]);
    }

    public function store(VariantStoreRequest $request, string $slug)
    {
        $product = Product::whereSlug($slug)->first();

        if (!$product) return $this->not_found('Sản phẩm không tồn tại');

        $validatedData = $request->validated();

        $variant = $product->variants()->create($validatedData);

        // Xử lí upload file
        $images = [];
        if ($request->hasFile(key: 'product_images')) {
            foreach ($request->file('product_images') as $file) {
                $images[] = [
                    'variant_id' => $variant->id,
                    'image_url' => $file->store('product_images'),
                ];
            }
        }

        $product->productImages()->createMany($images);

        $variant->variantValues()->attach($validatedData['variant_values']);

        $this->loadRelations($variant, $request, true);
        
        return $this->created("Tạo biến thể thành công", [
            'variant' => new VariantResource($variant),
        ]);
    }

    public function show(Request $request, string $slug, string $sku)
    {
        $product = Product::whereSlug($slug)->first();

        if (!$product) return $this->not_found('Sản phẩm không tồn tại');

        $variant = $product->variants()->whereSku($sku)->first();

        if (!$variant) return $this->not_found('Biến thể không tồn tại hoặc không thuộc sản phẩm này');

        $this->loadRelations($variant, $request, true);

        return $this->ok("Lấy thông tin biến thể thành công", [
            'variant' => new VariantResource($variant),
        ]);
    }

    public function update(VariantUpdateRequest $request, string $slug, string $sku)
    {
        $product = Product::whereSlug($slug)->first();

        if (!$product) return $this->not_found('Sản phẩm không tồn tại');

        $variant = $product->variants()->whereSku($sku)->first();

        if (!$variant) return $this->not_found('Biến thể không tồn tại hoặc không thuộc sản phẩm này');

        $validatedData = $request->validated();

        // Xử lí upload file
        $images = [];
        if ($request->hasFile(key: 'product_images')) {

            $this->delete_storage_product_images($variant);

            foreach ($request->file('product_images') as $file) {
                $images[] = [
                    'variant_id' => $variant->id,
                    'image_url' => $file->store('product_images'),
                ];
            }
        }

        $product->productImages()->createMany($images);

        $variant->variantValues()->sync($validatedData['variant_values'], false);

        $this->loadRelations($variant, $request, true);
        
        return $this->ok("Cập nhật biến thể thành công", [
            'variant' => new VariantResource($variant),
        ]);
        
    }

    public function destroy(string $slug, string $sku)
    {
        $product = Product::whereSlug($slug)->first();

        if (!$product) return $this->not_found('Sản phẩm không tồn tại');

        $variant = $product->variants()->whereSku($sku)->first();

        if (!$variant) return $this->not_found('Biến thể không tồn tại hoặc không thuộc sản phẩm này');

        $this->delete_storage_product_images($variant);

        $variant->delete();
        // ⬆️ đồng thời xóa bản ghi trong pivot_vv

        return $this->no_content();
    }

    protected function delete_storage_product_images(Variant $variant)
    {
        $variant -> loadMissing('productImages');
        
        foreach ($variant->productImages as $image) {

            $this->delete_storage_file($image,'image_url');

            $image->delete();
        }
    }
}
