<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\OrderStoreRequest;
use App\Http\Requests\V1\OrderUpdateRequest;
use App\Models\Order;
use App\Models\Voucher;
use App\Traits\ApiResponse;
use App\Traits\LoadRelations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ApiResponse, LoadRelations;

    protected $validRelations = [
        // 'user',
        'orderItems',
        'orderItems.variant',
        'orderItems.variant.product',
        'orderItems.variant.size',
        'orderItems.variant.color',
    ];

    public function index(Request $request)
    {
        /** @var \App\Models\User */
        $user = auth('sanctum')->user();

        $orders = $user->orders()->latest()->getQuery();

        $this->loadRelations($orders, $request);

        $this->applyFilters($orders, $request->query());

        return response()->json($orders->paginate(10));
    }

    public function show(Request $request, string $id)
    {
        /** @var \App\Models\User */
        $user = auth('sanctum')->user();

        $order = $user->orders()->find($id);

        if (!$order) return $this->not_found('Đơn hàng không tồn tại');

        $this->loadRelations($order, $request);

        return response()->json($order);
    }

    public function store(OrderStoreRequest $request)
    {
        $data = $request->validated();
 
        /** @var \App\Models\User */
        $user = auth('sanctum')->user();

        // lấy thông tin giỏ hàng
        $cartItems = $user->cartItems()->with([
            'variant.product:id,name,sku,thumb_image,price_regular,price_sale', 
            'variant.size:id,name', 
            'variant.color:id,name,code'
        ])->get();

        // Bắt đầu transaction
        return DB::transaction(function () use ($user, $data, $cartItems) {
            $totalPrice = $user->total_price;

            // kiểm tra nếu có voucher
            if ($data['voucher_code'] ?? false) {
                $voucher = Voucher::whereCode($data['voucher_code'])->first();

                $totalPrice -= (int) $voucher->discount;

                $voucher->increment('used_count');

                if ($voucher->max_usage !== null && $voucher->used_count >= $voucher->max_usage) {
                    $voucher->update(['is_active' => false]);
                }

                // (Tùy chọn) Cập nhật user_vouchers nếu là voucher cá nhân hóa
                // if (\App\Models\UserVoucher::where('user_id', $user->id)->where('voucher_id', $voucher->id)->exists()) {
                    // $userVoucher = \App\Models\UserVoucher::where('user_id', $user->id)
                        // ->where('voucher_id', $voucher->id)
                        // ->first();
                    // $userVoucher->increment('usage_count');
                    // $userVoucher->update(['is_used' => true]); // Đánh dấu đã dùng
                // }
            }

            // tạo order
            $order = $user->orders()->create(array_merge(
                $data,
                [
                    'order_sku'      => 'ORD-' . strtoupper(uniqid()),
                    'status_order'   => Order::STATUS_ORDER_PENDING,
                    'status_payment' => Order::STATUS_PAYMENT_UNPAID,
                    'total_price'    => $totalPrice,
                ]
            ));

            // chuyển data cartItems sang orderItems
            $orderItemsData = [];

            foreach ($cartItems as $cartItem) {
                $variant = $cartItem->variant;
                $product = $variant->product;

                // Kiểm tra tồn kho
                if ($cartItem->quantity > $variant->quantity) {
                    throw new \Exception("Sản phẩm {$product->name} ({$product->sku}) hiện đã hết hàng số lượng tồn kho.");
                }

                $orderItemsData[] = [
                    'order_id'              => $order->id,
                    'product_variant_id'    => $variant->id,
                    'quantity'              => $cartItem->quantity,
                    'product_name'          => $product->name,
                    'product_sku'           => $product->sku,
                    'product_img_thumbnail' => $product->thumb_image,
                    'product_price_regular' => $product->price_regular,
                    'product_price_sale'    => $product->price_sale,
                    'variant_size_name'     => $variant->size->name,
                    'variant_color_name'    => $variant->color->name,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ];
            }

            $order->orderItems()->insert($orderItemsData);

            // Xóa giỏ hàng
            $user->cartItems()->delete();

            // Giảm số lượng tồn kho trong product_variants
            foreach ($cartItems as $cartItem) {
                $cartItem->variant->decrement('quantity', $cartItem->quantity);
            }

            return $this->ok('Đơn hàng của bạn đã được tạo', $order);
        });
    }

    public function update(OrderUpdateRequest $request, string $id)
    {
        $order = Order::find($id);

        if (!$order) return $this->not_found('Đơn hàng không tồn tại');

        if ($order->status_order != Order::STATUS_ORDER_PENDING 
            || $order->status_payment != Order::STATUS_PAYMENT_UNPAID
        ) return $this->error('Không thể cập nhật vì đơn hàng đã được xử lý');

        $validatedData = $request->validated();

        return DB::transaction(function () use ($order, $validatedData) {
            // Cập nhật đơn hàng
            $order->update($validatedData);
    
            if ($order->status_order === Order::STATUS_ORDER_CANCELED) {
                // Lấy tất cả orderItems của đơn hàng
                $orderItems = $order->orderItems()->with('variant')->get();
    
                foreach ($orderItems as $orderItem) {
                    // Tăng lại số lượng tồn kho trong product_variants
                    $orderItem->variant->increment('quantity', $orderItem->quantity);
                }
            }
            
            return $this->ok("Cập nhật hóa đơn thành công", $order);
        });
    }

    private function applyFilters($orders, $queryParams)
    {
        // Tìm kiếm theo status
        if (!empty($queryParams['status_order'])) {
            $orders->statusOrderFilter($queryParams['status_order']);
        }
    }
}

// try {
//     DB::transaction(function () use ($request) {
//         // create ...
//     });
//     return $this->craeted('User and profile created successfully');
// } catch (\Exception $e) {
//     return $this->error('Error occurred: ' . $e->getMessage());
// }

// or

// DB::beginTransaction();
// try {
//     // create...
//     DB::commit();
// 
//     return $this->craeted('User and profile created successfully'); 
// } catch (\Exception $e) {
//     DB::rollBack();
//     return $this->error('Error occurred: ' . $e->getMessage()); 
// }