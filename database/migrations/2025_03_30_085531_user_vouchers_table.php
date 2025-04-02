<?php

use App\Models\User;
use App\Models\Voucher;
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
        Schema::create('user_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Voucher::class)->constrained()->cascadeOnDelete();
            $table->integer('usage_count')->default(0)->comment('Số lần sử dụng');
            $table->boolean('is_used')->default(false); // Trạng thái đã sử dụng hết hay chưa
            $table->dateTime('assigned_at')->nullable();
            $table->timestamps();

            // Đảm bảo mỗi user chỉ có 1 bản ghi cho mỗi voucher
            $table->unique(['user_id', 'voucher_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_vouchers');
    }
};
