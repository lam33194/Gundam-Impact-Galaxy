<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CartRequest;
use App\Services\Client\CartService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    use ApiResponseTrait;

    protected $cartService;
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Danh sách đơn hàng của tôi
     * 
     */
    public function index()
    {
        return $this->cartService->indexService();
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     * 
     * method : POST
     * 
     * product_variant_id : ID của biến thể sản phẩm
     * quantity : Số lượng
     * 
     * api/v1/carts
     */
    public function store(CartRequest $cartRequest)
    {
        try {

            $cart = $this->cartService->storeService($cartRequest->validated());

            return $this->successResponse($cart, 'Thao tác thành công !!!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {

            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    /**
     * Cập nhật số lượng từng sản phẩm trong giỏ hàng
     * 
     * method : PUT
     * 
     * quantity : Số lượng
     * 
     * api/v1/carts/{id}  : id của biến thể sản phẩm
     * 
     */
    public function update(CartRequest $cartRequest, string $id)
    {
        try {

            $cart = $this->cartService->updateService($cartRequest->validated(), $id);

            return $this->successResponse($cart, 'Thao tác thành công !!!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {

            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    /**
     * Xóa sản phẩm trong giỏ hàng
     * 
     * method : DELETE
     * 
     * 
     * api/v1/carts/{id}  
     * 
     * id == 0 => Xóa All Cart
     * id == biến thể sản phẩm => Xóa sản phẩm đó trong giỏ hàng
     */
    public function destroy(string $id)
    {
        try {
            $this->cartService->destroyService($id);

            return $this->successResponse([], 'Thao tác thành công !!!', Response::HTTP_OK);
        } catch (\Throwable $th) {

            return $this->errorResponse('Thao tác không thành công !!!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
