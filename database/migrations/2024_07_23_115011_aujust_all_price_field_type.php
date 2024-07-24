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
        Schema::table('customer', function (Blueprint $table) {
            $table->decimal('balance')->default(0)->comment('余额')->change();
        });
        Schema::table('customer_balance_record', function (Blueprint $table) {
            $table->decimal('balance')->comment('变动金额')->change();
        });
        Schema::table('mall_goods_sku', function (Blueprint $table) {
            $table->decimal('price')->comment('单价')->change();
        });
        Schema::table('mall_order', function (Blueprint $table) {
            $table->decimal('order_money')->comment('订单金额')->change();
            $table->decimal('order_fact_money')->comment('订单实付金额')->change();
        });
        Schema::table('mall_order_adjust', function (Blueprint $table) {
            $table->decimal('adjust_price')->comment('调整价格')->change();
        });
        Schema::table('mall_order_detail', function (Blueprint $table) {
            $table->decimal('goods_price')->comment('商品价格')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
