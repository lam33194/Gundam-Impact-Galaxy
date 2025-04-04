<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_ORDER = [
        self::STATUS_ORDER_PENDING    => 'Chờ xác nhận',
        self::STATUS_ORDER_CONFIRMED  => 'Đã xác nhận',
        self::STATUS_ORDER_PREPARING  => 'Đang chuẩn bị hàng',
        self::STATUS_ORDER_SHIPPING   => 'Đang vận chuyển',
        self::STATUS_ORDER_DELIVERED  => 'Đã giao hàng',
        self::STATUS_ORDER_CANCELED   => 'Đơn hàng đã bị hủy',
    ];

    public const STATUS_PAYMENT = [
        self::STATUS_PAYMENT_UNPAID => "Chưa thanh toán",
        self::STATUS_PAYMENT_PAID   => "Đã thanh toán",
        self::STATUS_PAYMENT_FAILED => "Thanh toán thất bại",
    ];

    public const TYPE_PAYMENT = [
        'vnpay' => "VNPAY",
        'momo'  => "MOMO",
        'cod'   => "Thanh toán khi nhận hàng"
    ];

    public const STATUS_ORDER_PENDING = 'pending';
    public const STATUS_ORDER_CONFIRMED = 'confirmed';
    public const STATUS_ORDER_PREPARING = 'preparing';
    public const STATUS_ORDER_SHIPPING = 'shipping';
    public const STATUS_ORDER_DELIVERED = 'delivered';
    public const STATUS_ORDER_CANCELED = 'canceled';
    
    public const STATUS_PAYMENT_UNPAID = 'unpaid';
    public const STATUS_PAYMENT_PAID = 'paid';
    public const STATUS_PAYMENT_FAILED = 'failed';

    public const TYPE_PAYMENT_VNPAY = 'vnpay';
    public const TYPE_PAYMENT_MOMO = 'momo';
    public const TYPE_PAYMENT_COD = 'cod';

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
        return $this->hasMany(OrderItems::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope
    public function scopeStatusOrderFilter($query, $status)
    {
        return $query->where('status_order', '=', $status);
    }
}
