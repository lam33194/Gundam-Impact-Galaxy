<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelExpiredVnpayOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cancel-expired-vnpay-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hủy đơn hàng sử dụng PTTT online nếu chưa được thanh toán sau 1 khoảng thời gian';

    // Huỷ sau 2 ngày
    // const EXPIRATION_MINUTES = 2880;
    const EXPIRATION_MINUTES = 5;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Bắt đầu tìm đơn hàng quá thời hạn thanh toán...');

        $expirationTime = Carbon::now()->subMinutes(self::EXPIRATION_MINUTES);

        // Find orders that meet the criteria
        $expiredOrders = Order::where([
            ['type_payment', Order::TYPE_PAYMENT_VNPAY],
            ['status_payment', Order::STATUS_PAYMENT_UNPAID],
            ['status_order', Order::STATUS_ORDER_PENDING],
            ['created_at', '<=', $expirationTime],
        ])->get();

        if ($expiredOrders->isEmpty()) {
            $this->info('Ko có đơn nào quá thời hạn thanh toán');
            return 0;
        }

        $this->info("Tìm thấy {$expiredOrders->count()} đơn hàng chưa thanh toán");

        foreach ($expiredOrders as $order) {
            DB::transaction(function () use ($order) {
                // 1. Update order status
                $order->status_order = Order::STATUS_ORDER_CANCELED;
                $order->status_payment = Order::STATUS_PAYMENT_FAILED;
                $order->save();

                // 2. Restore stock quantity
                try {
                    // Tăng lại số lượng tồn kho
                    $orderItems = $order->orderItems()->with('variant')->get();

                    foreach ($orderItems as $item) {
                        if ($item->variant) {
                            $item->variant->increment('quantity', $item->quantity);
                            $this->info("Tăng lại tồn kho [Variant-{$item->variant->id}] [Order-{$order->order_sku}]");
                        } else {
                            Log::warning("Không tìm thấy biến thể cho [OrderItem-{$item->id}] [Order-{$order->order_sku}]");
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Xảy ra lỗi khi cố gắng tăng lại tồn kho cho [Order-{$order->order_sku}]" . $e->getMessage());
                    throw $e; // Re-throw to trigger rollback
                }

                Log::info("Đơn {$order->order_sku} bị hủy do quá thời hạn thanh toán.");
                $this->info("Đã hủy: #{$order->id}-{$order->order_sku}");
            }); // End transaction
        }
        $this->info('Hoàn thành hủy đơn hàng');
        return 0;
    }
}
