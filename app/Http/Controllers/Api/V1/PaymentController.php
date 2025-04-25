<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponse;

    public function createPayment(Request $request, string $id)
    {
        $order = Order::find($id);

        if (!$order) return $this->not_found('Đơn hàng không tồn tại');
        
        if ($order->user->id != auth('sanctum')->id()) return $this->forbidden('Bạn không có quyền thực hiện chức năng này');

        // Kiểm tra trạng thái thanh toán (nếu đã thanh toán thì không xử lý lại)
        if ($order->status_payment === Order::STATUS_PAYMENT_PAID) {
            return $this->error('Đơn hàng của bạn đã được thanh toán');
        }

        // Kiểm tra trạng thái đơn hàng (chỉ cho phép thanh toán khi đơn hàng ở trạng thái phù hợp)
        if (!in_array($order->status_order, [Order::STATUS_ORDER_PENDING, Order::STATUS_ORDER_CONFIRMED])) {
            return $this->error('Đơn hàng này đã được xử lú');
        }

        switch ($order->type_payment) {
            case Order::TYPE_PAYMENT_VNPAY:
                $paymentUrl = $this->createVnPayPaymentUrl($request->merge([
                    'order_sku' => $order->order_sku,
                    'total'     => $order->total_price,
                ]));
            return $this->ok('Tạo URL thanh toán VNPAY thành công', $paymentUrl);

            case Order::TYPE_PAYMENT_MOMO:
                // 
            return;

            case Order::TYPE_PAYMENT_COD:
                // Với COD, không cần URL thanh toán, có thể cập nhật trạng thái trực tiếp nếu cần
                if ($order->status_order == Order::STATUS_ORDER_PENDING)
                    return $this->ok('Đơn hàng của bạn đang chờ xác nhận');

                else return $this->ok('Đơn hàng của bạn đang được xử lí, vui lòng chờ giao hàng');

            default:
                return $this->error('Phương thức thanh toán không hợp lệ');
        }
    }

    public function createVnPayPaymentUrl(Request $request)
    {
        $vnp_TmnCode = config('payment.vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('payment.vnpay.vnp_HashSecret');
        $vnp_Url = config('payment.vnpay.vnp_Url');
        $vnp_ReturnUrl = config('payment.vnpay.vnp_ReturnUrl');

        $vnp_TxnRef = $request->input('order_sku');
        $vnp_OrderInfo = "Thanh toán đơn hàng #" . $vnp_TxnRef;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $request->input('total') * 100;
        $vnp_Locale = 'vn';
        $vnp_IpAddr = $request->ip();
        $vnp_BankCode = $request->input('bank_code', '');

        // Dữ liệu gửi sang VNPAY
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
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        // Sắp xếp các tham số theo thứ tự alphabet
        ksort($inputData);

        // Tạo chuỗi hash
        $query = http_build_query($inputData);
        $vnp_SecureHash = hash_hmac('sha512', $query, $vnp_HashSecret);
        $vnp_Url .= '?' . $query . '&vnp_SecureHash=' . $vnp_SecureHash;

        return $vnp_Url;
    }

    public function vnpayReturn(Request $request)
    {
        $vnp_HashSecret = config('payment.vnpay.vnp_HashSecret');
        $vnp_ReturnData = $request->all();

        $vnp_SecureHash = $vnp_ReturnData['vnp_SecureHash'];
        unset($vnp_ReturnData['vnp_SecureHash']);

        ksort($vnp_ReturnData);
        $hashData = http_build_query($vnp_ReturnData);
        $computedHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        // Kiểm tra tính hợp lệ của dữ liệu trả về
        if ($computedHash !== $vnp_SecureHash) return $this->error('Dữ liệu trả về không hợp lệ');
        // if ($computedHash !== $vnp_SecureHash) {
        //     return redirect()->away(config('payment.frontend.payment_failed_url') . '?error=' . urlencode('Dữ liệu trả về không hợp lệ'));
        // }

        // Lấy order_sku từ vnp_TxnRef
        $orderSku = $vnp_ReturnData['vnp_TxnRef'];
        $order = Order::where('order_sku', $orderSku)->first();

        if (!$order) return $this->not_found('Đơn hàng không tồn tại');

        // Kiểm tra trạng thái giao dịch từ VNPAY
        $responseCode = $vnp_ReturnData['vnp_ResponseCode'];
        if ($responseCode === '00') {
            // Thanh toán thành công
            $order->update([
                'status_payment' => Order::STATUS_PAYMENT_PAID,
                'status_order'   => Order::STATUS_ORDER_CONFIRMED,
            ]);

            // return $this->ok('Thanh toán thành công!', $order);
            return redirect()->away(config('payment.frontend.payment_success_url') . '?' . $hashData);
        } else {
            // Thanh toán thất bại
            $order->update([
                'status_payment' => Order::STATUS_PAYMENT_UNPAID,
                'status_order'   => Order::STATUS_ORDER_PENDING,
            ]);

            // return $this->error('Thanh toán thất bại. Vui lòng thử lại sau', 400, [
            //     'response_code' => $responseCode,
            // ]);
            return redirect()->away(config('payment.frontend.payment_failed_url') . '?' . $hashData);
        }
    }

    public function createMomoPaymentUrl(Request $request)
    {
        // 
    }
}