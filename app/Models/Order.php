<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

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
    public const TYPE_PAYMENT_COD = 'cod';
}
