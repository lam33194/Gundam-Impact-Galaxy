<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CartItemStoreRequest;
use App\Http\Requests\V1\UserStoreRequest;
use App\Http\Requests\V1\UserUpdateRequest;
use App\Http\Resources\V1\CartItemResource;
use App\Http\Resources\V1\UserResource;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use App\Traits\ApiResponse;
use App\Traits\StorageFile;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse, StorageFile;

    public function index(Request $request)
    {
        $users = User::query();

        return $this->ok("Lấy danh sách người dùng thành công", [
            'users' => UserResource::collection($users->get())
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('avatar')) {
            // upload file vào storage
            $avatarPath = $request->file('avatar')->store('avatars');
            $validatedData['avatar'] = $avatarPath;
        }

        $user = User::create($validatedData);

        return $this->created("Tạo người dùng thành công", [
            'user' => new UserResource($user),
        ]);
    }

    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) return $this->not_found("Người dùng không tồn tại");

        return $this->ok("Lấy thông tin người dùng thành công", [
            'user' => new UserResource($user),
        ]);
    }

    public function update(UserUpdateRequest $request, string $id)
    {
        $user = User::find($id);

        if (!$user) return $this->not_found("Người dùng không tồn tại");

        // validated data
        $validatedData = $request->validated();

        if ($request->hasFile('avatar')) {
            $this->delete_storage_file($user, 'avatar');

            // upload file vào storage
            $avatarPath = $request->file('avatar')->store('avatars');
            $validatedData['avatar'] = $avatarPath;
        }

        $user->update($validatedData);

        return $this->ok("Cập nhật thành công", [
            'user' => new UserResource($user),
        ]);
    }

    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) return $this->not_found("Người dùng không tồn tại");

        $this->delete_storage_file($user, 'avatar');

        $user->delete();

        return $this->no_content();
    }

    public function add_to_cart(CartItemStoreRequest $request, string $id)
    {
        // userId, productId, variantId, quantity
        $validatedData = $request->toArray();

        $user = User::find($id);

        $productId  = $validatedData['productId'];
        $variantId  = $validatedData['variantId'];
        $quantity   = $validatedData['quantity'];

        $product = Product::find($productId);
        $variant = Variant::with('variantValues')->find($variantId);

        // nếu nhập sai variantId
        if ($variant->product != $product) return $this->not_found('Biến thể không tồn tại hoặc không thuộc sản phẩm này');

        if ($quantity > $variant->stock) return $this->failedValidation('Số lượng sản phẩm phải thấp hơn số hàng tồn kho');

        // bổ sung thông tin snapshot của biến thể
        $validatedData['variant_name'] = $variant->variant_name;
        $validatedData['sku']          = $variant->sku;
        $validatedData['extra_price']  = $variant->extra_price;
        foreach ($variant->variantValues as $value) {
            $validatedData['attributes'][] = [$value->variantAttribute->name => $value->value];
        }

        // lấy thông tin sản phẩm trong giỏ hàng của user
        $cartItem = $user->cartItems()->where([
            'product_id' => $productId,
            'variant_id' => $variantId,
        ])->first();

        // nếu user đã có sản phẩm này trong giỏ hàng 
        if ($cartItem) {

            if ($quantity > 0) {

                // cập nhật số lượng sản phẩm và thuộc tính biến thể trong giỏ hàng  
                $cartItem->update([
                    'quantity'   => $quantity,
                    'attributes' => $validatedData['attributes'],
                ]);

                return $this->ok('Lưu giỏ hàng thành công', [
                    'cartItem' => new CartItemResource($cartItem)
                ]);
            } else {
                // nếu nhập số lượng = 0 (quantity min:0)
                $cartItem->delete();
                return $this->ok('Đã xóa sản phẩm khỏi giỏ hàng');
            }

        // nếu sản phẩm chưa có trong giỏ hàng
        } else {

            // thêm mới sản phẩm vào giỏ hàng nên quantity ít nhất >= 1
            if ($quantity <= 0) return $this->failedValidation('Vui lòng chọn ít nhất 1 số lượng sản phẩm');

            // thểm thông tin sản phẩm vào giỏ hàng
            $cartItem = $user->cartItems()->create(array_merge(
                $validatedData,
                [
                    'product_price' => $product->price,
                    'product_name'  => $product->name,
                ]
            ));

            return $this->created('Thêm vào giỏ hàng thành công', [
                'cartItem' => new CartItemResource($cartItem),
            ]);
        }
    }

    public function clear_cart_items(string $id)
    {
        $user = User::find($id);

        if (!$user) $this->not_found('Người dùng không tồn tại');

        $user->cartItems()->delete();

        return $this->no_content();
    }
}
