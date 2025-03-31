<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Toastr;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductGallery;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private const VIEW_PATH = 'admin.products.';

    public function index()
    {
        $products = Product::query()->latest('id')->paginate(20);
        return view(self::VIEW_PATH . __FUNCTION__, compact('products'));
    }

    public function create()
    {
        $categories = Category::query()->latest()->get();
        $colors = ProductColor::query()->latest()->get();
        $sizes = ProductSize::query()->latest()->get();
        $tags = Tag::query()->latest('id')->get();

        return view(self::VIEW_PATH . __FUNCTION__, compact(['categories', 'tags', 'colors', 'sizes', 'tags']));
    }

    public function store(StoreProductRequest $request)
    {
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

            Toastr::success(null, "Thêm sản phẩm thành công");
            return redirect()->route('admin.products.index');
        } catch (\Exception $e) {
            DB::rollBack();

            if (!empty($dataProduct['thumb_image']) && Storage::exists($dataProduct['thumb_image'])) {
                Storage::delete($dataProduct['thumb_image']);
            };

            foreach (array_merge($dataProductVariants, $dataProductGalleries) as $item) {
                if (!empty($item['image']) && Storage::exists($item['image'])) {
                    Storage::delete($item['image']);
                }
            }

            Toastr::error(null, 'Đã xảy ra lỗi');
            Log::error($e->getMessage());
            return redirect()->back();
        }
    }

    public function show(Product $product)
    {
        return view(self::VIEW_PATH . __FUNCTION__, compact('product'));
    }

    public function edit(Product $product)
    {
        // $product->load(['']);
        return view(self::VIEW_PATH . __FUNCTION__, compact('product'));
    }

    public function update(Request $request, string $id)
    {
        //
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
}
