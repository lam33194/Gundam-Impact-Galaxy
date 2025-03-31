<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OrderRequest;
use App\Models\Order;
use App\Models\UserVoucher;
use App\Services\Client\OrderService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class OrderController extends Controller
{
    use ApiResponseTrait;
    private $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function store(OrderRequest $orderRequest)
    {
        try {
            $data = $orderRequest->validated();

            if ($data['type_payment'] === Order::TYPE_PAYMENT_COD) {
                $Order = $this->orderService->storeService($data);
            }

            $Order = $this->orderService->paymentService($data);


            return $this->successResponse($Order, 'Thao tác thành công !!!', Response::HTTP_CREATED);
        } catch (\Throwable $th) {

            return $this->errorResponse($th->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function checkout(Request $request)
    {
        $resultCode = $request->query('resultCode', '');
        $vnp_TransactionStatus = $request->query('vnp_TransactionStatus', '');
        $vnp_TxnRef = $request->query('vnp_TxnRef', '');
        $orderId = $request->query('orderId', '');
        $frontendUrl = "http://localhost:3000";

        $txnRef = $orderId ?: $vnp_TxnRef;
        if (!$txnRef) {
            return redirect($frontendUrl);
        }


        $orderData = Redis::get("order:$txnRef");
        if (!$orderData) {
            return redirect($frontendUrl);
        }

        $orderData = json_decode($orderData, true);



        $isSuccess = ($resultCode === "0" || $vnp_TransactionStatus === "00");

        if ($isSuccess) {
            // return $orderData;
            DB::transaction(function () use ($orderData) {

                $orderData['data']['status_payment'] = Order::STATUS_PAYMENT_PAID;
                // Log::info($orderData['data']);
                $this->orderService->storeService($orderData['data']);

                if (
                    !empty($orderData['data']['voucher_code']) &&
                    !empty($orderData['data']['voucher_id']) &&
                    !empty($orderData['data']['user_id'])
                ) {

                    $userVoucher = UserVoucher::where('voucher_id', $orderData['data']['voucher_id'])
                        ->where('user_id', $orderData['data']['user_id'])
                        ->first();

                    if ($userVoucher) {
                        $userVoucher->decrement('usage_count', 1);
                    }
                }

            });

            Redis::del("order:$txnRef");


            return redirect($frontendUrl . '/booking-success')->withCookies([
                cookie('order_id', $txnRef, 10),
                cookie('status', true),
                cookie('message', 'Thanh toán thành công'),
            ]);
        }

        Redis::del("order:$txnRef");
        return redirect($frontendUrl);
    }
}
