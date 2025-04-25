<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('user_name', 255);
            $table->string('user_email', 255);
            $table->string('user_phone', 255);
            $table->string('user_address', 255);
            $table->string('user_note', 255)->nullable();

            $table->boolean('same_as_buyer')->default(true);
            $table->string('order_sku')->unique();
            $table->string('type_payment', 50)->default(Order::TYPE_PAYMENT_COD);

            $table->string('ship_user_name', 255)->nullable();
            $table->string('ship_user_email', 255)->nullable();
            $table->string('ship_user_phone', 255)->nullable();
            $table->string('ship_user_address', 255)->nullable();
            $table->string('ship_user_note', 255)->nullable();

            $table->string('status_order', 50)->default(Order::STATUS_ORDER_PENDING);
            $table->string('status_payment', 50)->default(Order::STATUS_PAYMENT_UNPAID);

            $table->unsignedBigInteger('total_price')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
