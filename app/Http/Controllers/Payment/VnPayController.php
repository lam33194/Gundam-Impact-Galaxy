<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OrderResource;
use App\Models\Order;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class VnPayController extends Controller
{
    use ApiResponse;

    public function createPayment(Request $request)
    {
        $validatedData = $request->validate([
            'orderId' => 'required|exists:orders,id',
        ], [

        ]);
        
        $order = Order::find($validatedData['orderId']);
        
        $payment = $order->payment;

        if ($payment->payment_method != 'vnpay') return $this->error('Vui lòng đổi phương thức thanh toán sang VNPay trước khi đơn hàng của bạn được xử lý'); 

        switch ($payment->status) {
            case 'completed':
                return $this->error('Thanh toán đã hoàn tất, không thể tạo URL thanh toán mới.');

            case 'processing':
                if ($payment->payment_url ?? false) {
                    return $this->ok('Vui lòng tiếp tục thanh toán đơn hàng của bạn', [
                        'paymentUrl' => $payment->payment_url
                    ]);
                }
            break;

            case 'failed':
            case 'cancelled':
                $payment->update(['status' => 'pending']);
            break;
        }

        if ($order->status !== 'pending') return $this->error('Đơn hàng của bạn đang được xử lý');

        // Tạo url thanh toán
        $paymentUrl = $this->createPaymentUrl(new Request([
            'orderCode' => $order->order_code,
            'amount' => $order->total_amount,
        ]));

        $payment->update([
            'status'      => 'processing',
            'payment_url' => $paymentUrl
        ]);
        // $payment->update(['status' => 'processing']);

        return $this->ok('Vui lòng thanh toán qua VNPay', [
            $paymentUrl
        ]);
    }

    public function createPaymentUrl(Request $request)
    {
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_ReturnUrl = config('vnpay.vnp_ReturnUrl');

        $vnp_TxnRef = $request->input('orderCode');
        $vnp_OrderInfo = "Thanh toán đơn hàng #" . $vnp_TxnRef;
        $vnp_OrderType = 'billpayment'; // Loại hàng hóa
        $vnp_Amount = $request->input('amount') * 100;
        $vnp_Locale = 'vn'; // Ngôn ngữ
        $vnp_IpAddr = request()->ip();
        $vnp_BankCode = request()->bank_code;

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
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_SecureHash = $request->input('vnp_SecureHash');
        $inputData = $request->except('vnp_SecureHash');

        ksort($inputData);
        $query = http_build_query($inputData);
        $hash = hash_hmac('sha512', $query, $vnp_HashSecret);

        // Kiểm tra chữ ký
        if ($hash !== $vnp_SecureHash) return $this->error('Chữ ký không hợp lệ', data: [
            'responseCode' => '97',
        ]);

        $order = Order::where('order_code', $inputData['vnp_TxnRef'])->first();

        if (!$order) return $this->not_found('Không tìm thấy đơn hàng');

        $payment = $order->payment;

        switch ($inputData['vnp_ResponseCode']) {
            case '00':
                $order->payment()->update([
                    'status' => 'completed',
                    'payment_url' => null,
                    'amount' => $inputData['vnp_Amount']/100,
                    'transaction_id' => $inputData['vnp_TransactionNo'],
                    'paid_at' => $inputData['vnp_PayDate'],
                    'response_code' => $inputData['vnp_ResponseCode'],
                ]);

                $order->update(['status' => 'processing']);
            break;

            case '01':
                $payment->update(['status' => 'processing', 'response_code' => $inputData['vnp_ResponseCode']]);
            break;

            case '02':
            case '04':
                $payment->update(['status' => 'failed', 'response_code' => $inputData['vnp_ResponseCode']]);
            break;

            case '07':
                $payment->update(['status' => 'cancelled', 'response_code' => $inputData['vnp_ResponseCode']]);
            break;

            default:
                $payment->update([
                    'status' => 'failed',
                    'response_code' => $inputData['vnp_ResponseCode']
                ]);
            break;
        }

        $order->load('payment');

        return $this->ok('', [
            'order' => new OrderResource($order),
            'responseCode' => $inputData['vnp_ResponseCode'],
            'data' => $inputData,
        ]);
    }
}
// amount          = ?vnp_Amount
// order_code      = &vnp_TxnRef
// payment_method  = vnpay
// status          = &vnp_TransactionStatus ?
// transaction_id  = &vnp_TransactionNo
// paid_at         = &vnp_PayDate
// response_code   = &vnp_ResponseCode

// &vnp_BankCode=NCB
// &vnp_BankTranNo=VNP14868710
// &vnp_SecureHash
// &vnp_OrderInfo
// &vnp_CardType