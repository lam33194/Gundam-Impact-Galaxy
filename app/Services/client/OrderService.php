<?php

namespace App\Services\Client;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use PhpParser\Node\Stmt\Foreach_;

class OrderService
{
    use ApiResponseTrait;
    private const PATH_URL = "https://datn-gundam.me";
    public static function generateOrderId()
    {
        return substr(time(), -6) . mt_rand(100000, 999999);
    }
    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
    public function paymentService(array $data)
    {
        $CartItems = CartItem::with([
            'productVariant.product',
            'productVariant.color',
            'productVariant.size'
        ])->where('user_id', $data['user_id'])->get();

        if ($CartItems->isEmpty()) {
            throw new \Exception("Giỏ hàng của bạn đang trống.");
        }

        $data['type_payment'] == 'momo' ? $paymentResult = $this->processPayment($data) : $paymentResult = $this->processVnPayPayment($data);
        
        if (!isset($paymentResult)) {
            return response()->json(['message' => 'Lỗi khi tạo đơn hàng MoMo'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'url' => $paymentResult
        ]);
    }

    private function processPayment($dataRequest)
    {

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $orderId = self::generateOrderId();
        $redirectUrl = env('APP_URL') . '/api/v1/checkout';
        $ipnUrl = "https://hehe.test/check-out";
        $extraData = "";

        $requestId = time() . "";
        $requestType = "payWithATM";

        $rawHash = "accessKey=" . $accessKey . "&amount=" .  $dataRequest['total_price'] . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $dataRequest['total_price'],
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        $result = $this->execPostRequest($endpoint, json_encode($data));

        $jsonResult = json_decode($result, true);

        if (!isset($jsonResult['payUrl'])) {
            return null;
        }

        Redis::setex("order:$orderId", 900, json_encode([
            'order_id' => $orderId,
            'data' => $dataRequest,
        ]));

        return $jsonResult['payUrl'];
    }


    private function processVnPayPayment($dataRequest)
    {

        if (!isset($dataRequest['total_price'])) {
            return response()->json(['message' => 'Dữ liệu không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = self::PATH_URL . "/api/v1/checkout";
        $vnp_TmnCode = 'CW3MWMKN';
        $vnp_HashSecret = "2EQ9DCNFBR3H0GRQ4RCVHYTO1VZYXFLZ";
        $vnp_Locale = 'vn';
        // $vnp_BankCode = 'NCB';
        $vnp_TxnRef = self::generateOrderId();
        $vnp_Amount = $dataRequest['total_price'] * 100;
        $vnp_IpAddr = request()->ip();
        $vnp_OrderInfo = "Thanh toán Vnpay";
        $vnp_OrderType = "Thanh toán hóa đơn";


        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        ];

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);

        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;

        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        Redis::setex("order:$vnp_TxnRef", 900, json_encode([
            'order_id' => $vnp_TxnRef,
            'data' => $dataRequest,
        ]));
        return $vnp_Url;
    }


    

    public function storeService(array $data)
    {
        $data['order_sku'] = strtoupper(uniqid());

        $CartItems = CartItem::with([
            'productVariant.product',
            'productVariant.color',
            'productVariant.size'
        ])->where('user_id', $data['user_id'])->get();

        return DB::transaction(function () use ($data, $CartItems) {

            $Order = Order::create($data);

            if ($CartItems->isEmpty()) {
                throw new \Exception("Giỏ hàng của bạn đang trống.");
            }

            foreach ($CartItems as $CartItem) {
                $variant = ProductVariant::find($CartItem->product_variant_id);

                if (!$variant || $variant->quantity < $CartItem->quantity) {
                    throw new \Exception("Sản phẩm '{$CartItem->productVariant->product->name}' không đủ hàng trong kho.");
                }

                OrderItem::create([
                    'order_id'              => $Order->id,
                    'product_variant_id'    => $CartItem->product_variant_id,
                    'quantity'              => $CartItem->quantity,
                    'product_name'          => $CartItem->productVariant->product->name,
                    'product_sku'           => $CartItem->productVariant->product->sku,
                    'product_img_thumbnail' => $CartItem->productVariant->product->thumb_image,
                    'product_price_regular' => $CartItem->productVariant->product->price_regular,
                    'product_price_sale'    => $CartItem->productVariant->product->price_sale,
                    'variant_size_name'     => optional($CartItem->productVariant->size)->name,
                    'variant_color_name'    => optional($CartItem->productVariant->color)->name,
                ]);

                $CartItem->delete();

                $variant->decrement('quantity', $CartItem->quantity);
            }

            return $Order;
        });
    }
}
