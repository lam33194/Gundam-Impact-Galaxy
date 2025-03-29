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

    public const STATUS_ORDER_PENDING = 'pending';
    public const STATUS_ORDER_CONFIRMED = 'confirmed';
    public const STATUS_ORDER_PREPARING_GOODS = 'preparing_goods';
    public const STATUS_ORDER_SHIPPING = 'shipping';
    public const STATUS_ORDER_DELIVERED = 'delivered';
    public const STATUS_ORDER_CANCELED = 'canceled';
    public const STATUS_PAYMENT_UNPAID = 'unpaid';
    public const STATUS_PAYMENT_PAID = 'paid';
    public const TYPE_PAYMENT_VNPAY = 'vnpay';
    public const TYPE_PAYMENT_MOMO = 'momo';
    public const TYPE_PAYMENT_PAY_DELIVERY = 'pay_delivery';

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_phone',
        'user_address',
        'user_note',
        'same_as_buyer',
        'ship_user_name',
        'ship_user_email',
        'ship_user_phone',
        'ship_user_address',
        'ship_user_note',
        'status_order',
        'status_payment',
        'type_payment',
        'total_price',
        'order_sku'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
