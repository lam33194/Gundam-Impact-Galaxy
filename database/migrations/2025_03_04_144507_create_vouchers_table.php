<?php

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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->decimal('discount', 12, 0)->default(0);
            $table->boolean('is_active')->default(1)->comment("0 : Ngừng hoạt động , 1 : Hoạt động");
            $table->decimal('min_order_amount', 12, 0)->nullable(); // Giá trị đơn hàng tối thiểu
            $table->integer('used_count')->default(0); // Số lần được sử dụng
            $table->integer('max_usage')->nullable(); // số lần sử dụng tối đa
            // $table->enum('discount_type', ['fixed', 'percentage']); // Loại giảm giá
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
