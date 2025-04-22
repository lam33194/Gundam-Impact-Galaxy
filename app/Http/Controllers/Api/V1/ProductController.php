<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse, LoadRelations;

    protected $validRelations = [
        'variants',
        'variants.color',
        'variants.size',
        'galleries',
        'tags',
        'category',
    ];

    public function index(Request $request)
    {
        $products = Product::query()->latest();

        $this->loadRelations($products, $request);

        // $this->loadSubRelations($products);

        $this->applyFilters($products, $request->query());

        $perPage = request()->query('per_page', 10);

        return response()->json($products->paginate($perPage)->appends($request->query()));
    }

    public function show(string $slug)
    {
        $product = Product::whereSlug($slug)->first();

        if(!$product) return $this->not_found('Sản phẩm không tồn tại');

        $this->loadRelations($product, request(), true);

        // $this->loadSubRelations($product, true);

        return response()->json($product);
    }

    // Lấy danh sách products của category 
    public function getByCategory(Request $request, string $slug)
    {
        $category = Category::whereSlug($slug)->first();

        if (!$category) return $this->not_found("Danh mục không tồn tại");

        $products = $category->products()->getQuery();

        $this->loadRelations($products, $request);

        // $this->loadSubRelations($products);

        $this->applyFilters($products, $request->query());

        $perPage = request()->query('per_page', 10);

        return response()->json($products->paginate($perPage)->appends($request->query()));
    }

    public function getTopRevenueProducts()
    {
        $perPage = request()->query('per_page', 10);

        $topProducts = Product::select('products.*')
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->join('order_items', 'product_variants.id', '=', 'order_items.product_variant_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status_order', Order::STATUS_ORDER_DELIVERED)
            ->selectRaw('SUM(
                CASE 
                    WHEN order_items.product_price_sale > 0 THEN order_items.product_price_sale 
                    ELSE order_items.product_price_regular 
                END * order_items.quantity
            ) as total_revenue')
            ->groupBy('products.id', 'products.name', 'products.slug')
            ->orderByDesc('total_revenue');

        $this->loadRelations($topProducts, request());

        return response()->json($topProducts->paginate($perPage));
    }

    function getTopSellingProducts()
    {
        $perPage = request()->query('per_page', 10);

        $topProducts = Product::select('products.*')
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->join('order_items', 'product_variants.id', '=', 'order_items.product_variant_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status_order', Order::STATUS_ORDER_DELIVERED)
            ->selectRaw('SUM(order_items.quantity) as total_quantity')
            ->groupBy('products.id', 'products.name', 'products.slug')
            ->orderByDesc('total_quantity');

        $this->loadRelations($topProducts, request());

        return response()->json($topProducts->paginate($perPage));
    }

    // còn thiếu: lọc theo price_sale. Sắp xếp theo views, time, price
    private function applyFilters($product, $queryParams)
    {
        // Tìm kiếm theo tên
        if (!empty($queryParams['name'])) {
            $product->nameFilter($queryParams['name']);
        }

        // Tìm kiếm theo slug
        if (!empty($queryParams['slug'])) {
            $product->slugFilter($queryParams['slug']);
        }

        // Tìm kiếm theo sku
        if (!empty($queryParams['sku'])) {
            $product->skuFilter($queryParams['sku']);
        }

        // Lọc theo giá
        if (isset($queryParams['min_price']) || isset($queryParams['max_price'])) {
            $product->priceRangeFilter($queryParams['min_price'] ?? null, $queryParams['max_price'] ?? null);
        }

        $boolean_fields = ['is_active', 'is_hot_deal', 'is_good_deal', 'is_new', 'is_show_home'];

        foreach ($boolean_fields as $field) {
            if (isset($queryParams[$field])) {
                $product->where($field, filter_var($queryParams[$field], FILTER_VALIDATE_BOOLEAN));
            }
        }
    }

    // private function loadSubRelations($user, bool $isInstance = false)
    // {
    //     $getMethod = $isInstance ? 'getRelations' : 'getEagerLoads';

    //     $loadMethod = $isInstance ? 'loadMissing' : 'with';

    //     if (array_key_exists('variants', $user->$getMethod())) {
    //         $user->$loadMethod([
    //             'variants.color:id,name,code',
    //             'variants.size:id,name',
    //         ]);
    //     }
    // }
}
