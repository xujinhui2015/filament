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
        Schema::create('mall_cart', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->comment('会员ID');
            $table->foreignId('goods_id');
            $table->foreignId('goods_sku_id');
            $table->unsignedMediumInteger('goods_number')->comment('购买商品数量');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mall_cart');
    }
};
