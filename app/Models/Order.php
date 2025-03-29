<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_ORDER = [
        'pending'           => 'Chờ xác nhận',
        'confirmed'         => 'Đã xác nhận',
        'preparing_goods'   => 'Đang chuẩn bị hàng',
        'shipping'          => 'Đang vận chuyển',
        'delivered'         => 'Đã giao hàng',
        // 'canceled'          => 'Đơn hàng đã bị hủy',
    ];
    public const STATUS_PAYMENT = [
        'unpaid'            => "Chưa thanh toán",
        'paid'              => "Đã thanh toán"
    ];

    public const TYPE_PAYMENT = [
        'vnpay'            => "VNPAY",
        'momo'             => "MOMO",
        'pay_delivery'      => "Thanh toán khi nhận hàng"
    ];

   

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
