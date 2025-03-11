<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProductStoreRequest;
use App\Http\Resources\V1\ProductResource;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    // tên quan hệ hợp lệ (để check trên url)
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
        $data = $request->validated();

        // ...
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

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
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
            $query->priceFilter($request->query('minPrice', 0), $request->query('maxPrice',999999999));
        }
    }
}
