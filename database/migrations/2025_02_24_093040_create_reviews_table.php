<?php

use App\Models\Product;
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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class) -> constrained() -> cascadeOnDelete();
            $table->foreignIdFor(Product::class) -> constrained() -> cascadeOnDelete();
            $table->text('comment') -> nullable();
            $table->unsignedTinyInteger('rating') -> check('rating >= 1 AND rating <= 10') -> nullable(); // (1 -> 10⭐)
            $table->string('is_hidden')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
