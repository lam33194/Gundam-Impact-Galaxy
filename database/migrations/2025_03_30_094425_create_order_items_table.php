<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\Variant;
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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\ProductVariant::class);
            $table->unsignedBigInteger('quantity')->default(0);

            $table->string('product_name');
            $table->string('product_sku');
            $table->string('product_img_thumbnail')->nullable();
            $table->decimal('product_price_regular', 20, 0)->default(0);
            $table->decimal('product_price_sale', 20, 0)->default(0);

            $table->string('variant_size_name');
            $table->string('variant_color_name');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
