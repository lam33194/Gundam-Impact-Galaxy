<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use Illuminate\Database\Eloquent\Builder;
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
        $products = Product::query()->active()->select(
            'id',
            'category_id',
            'name',
            'slug',
            'sku',
            'thumb_image',
            'price_regular',
            'price_sale',
            'description',
            'is_active',
            'is_hot_deal',
            'is_good_deal',
            'is_new',
            'is_show_home',
        );

        $this->loadRelations($products, $request);

        // $this->loadSubRelations($products);

        $this->applyFilters($products, $request->query());

        $perPage = request()->query('per_page', 10);

        return response()->json($products->paginate($perPage)->appends($request->query()));
    }

    public function show(string $slug)
    {
        $product = Product::active()->whereSlug($slug)->first();

        if(!$product) return $this->not_found('Sản phẩm không tồn tại');

        $this->loadRelations($product, request(), true);

        // $this->loadSubRelations($product, true);

        return response()->json($product);
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

    private function applyFilters(Builder $product, $queryParams)
    {
        // Apply search filters (name, sku, id)
        if (!empty($queryParams['search'])) {
            $search = $queryParams['search'];
            $product->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Lọc theo giá
        if (isset($queryParams['min_price']) || isset($queryParams['max_price'])) {
            $product->priceRangeFilter($queryParams['min_price'] ?? null, $queryParams['max_price'] ?? null);
        }

        // Lọc theo tags
        if (!empty($queryParams['tags'])) {
            $tags = is_array($queryParams['tags']) ? $queryParams['tags'] : explode(',', $queryParams['tags']);
            $product->tagFilter($tags);
        }

        // Sắp xếp theo: giá / đánh giá
        if (!empty($queryParams['sort_direct']) && !empty($queryParams['sort_by'])) {
            $sortDirect = strtolower($queryParams['sort_direct']) === 'asc' ? 'asc' : 'desc';
            switch ($queryParams['sort_by']) {
                case 'price':
                    $product->orderByRaw(
                        '(CASE WHEN price_sale IS NOT NULL AND price_sale != 0 THEN price_sale ELSE price_regular END) ' . $sortDirect
                    );
                break;

                // case 'average_rating':
            }
        }

        // Lọc theo danh mục
        if (!empty($queryParams['category'])) {
            $product->where('category_id', $queryParams['category']);
        }
    }
    
    public function getRelatedProducts(string $slug)
    {
        $product = Product::with(['tags', 'category'])->whereSlug($slug)->first();

        if(!$product) return $this->not_found('Sản phẩm không tồn tại');

        // Lấy 8 sản phẩm liên quan
        $relateProducts = Product::active()->select(
            'id',
            'category_id',
            'name',
            'slug',
            'sku',
            'thumb_image',
            'price_regular',
            'price_sale',
        )->where('id', '!=', $product->id)
            ->where(function ($query) use ($product) {
                // Sản phẩm chung danh mục
                if ($product->category) {
                    $query->orWhere('category_id', $product->category->id);
                }

                // Sản phẩm chung tag
                if ($product->tags->isNotEmpty()) {
                    $tagIds = $product->tags->pluck('id')->toArray();
                    $query->orWhereHas('tags', function ($q) use ($tagIds) {
                        $q->whereIn('tags.id', $tagIds);
                    });
                }
            })
            ->with(['tags', 'category'])
            ->take(8)
            ->get();

        return $this->ok('Lấy sản phẩm liên quan thành công', $relateProducts);
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
