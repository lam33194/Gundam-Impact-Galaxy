<?php

use Illuminate\Support\Str;

/**
 *
 * @var Str @abstractStr
 *
 */

if (!function_exists('activeMenu')) {
    function activeMenu($uri)
    {
        return Str::startsWith(request()->path(), $uri) ? 'active' : '';
    }
}

if (!function_exists('activeMenuLi')) {
    function activeMenuLi($uri)
    {
        return Str::startsWith(request()->path(), $uri) ? 'mm-active' : '';
    }
}

if (!function_exists('activeTab')) {
    function activeTab($tabName)
    {
        // Lấy giá trị của tham số 'tab' từ URL hiện tại
        $currentTab = request()->query('tab', 'all'); // Mặc định là 'all' nếu không có tham số 'tab'

        // So sánh giá trị của tham số 'tab' với tên của tab truyền vào
        return $currentTab === $tabName ? 'active' : '';
    }
}

if (!function_exists('formatPrice')) {
    function formatPrice($price)
    {
        return number_format($price);
    }
}

if (!function_exists('limitTextLeng')) {
    function limitTextLeng($text, $limit, $end = "...")
    {
        return Str::length($text) > $limit ? Str::limit($text, $limit, $end) : $text;
    }
}

if (!function_exists('calculateProductSubTotal')) {
    function calculateProductSubTotal($price, $quantity)
    {
        return number_format($price * $quantity);
    }
}

if (!function_exists('matchStatusOrder')) {
    function matchStatusOrder($status)
    {
        return match ($status) {
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'preparing_goods' => 'Đang chuẩn bị hàng',
            'shipping' => 'Đang vận chuyển',
            'delivered' => 'Đã giao hàng',
            'canceled' => 'Đơn hàng đã bị hủy',
        };
    }
}

if (!function_exists('matchStatusPayMent')) {
    function matchStatusPayMent($status)
    {
        return match ($status) {
            'unpaid' => "Chưa thanh toán",
            'paid' => "Đã thanh toán"
        };
    }
}

if (!function_exists('statusOrderClass')) {
    function statusOrderClass($status)
    {
        return match ($status) {
            'pending' => 'bg-warning text-white',
            'confirmed' => 'bg-primary text-white',
            'preparing_goods' => 'bg-info text-white',
            'shipping' => 'bg-secondary text-white',
            'delivered' => 'bg-success text-white',
            'canceled' => 'bg-danger text-white',
        };
    }
}

if (!function_exists('statusPaymentClass')) {
    function statusPaymentClass($status)
    {
        return match ($status) {
            'unpaid' => 'bg-danger text-white',
            'paid' => 'bg-primary text-white'
        };
    }
}

if (!function_exists('matchRatings')) {
    function matchRatings($rating)
    {
        return match ($rating) {
            5 => '100%',
            4 => '80%',
            3 => '60%',
            2 => '40%',
            1 => '20%',
            0 => '0%',
        };
    }
}

if (!function_exists('getCountCart')) {
    function getCountCart()
    {
        $total = count(session('cart'));
    }
}

if (!function_exists('calSubTotal')) {
    function calSubTotal($quantity, $price)
    {
        return formatPrice($quantity * $price);
    }
}
