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
        Schema::create('mall_refund_address', function (Blueprint $table) {
            $table->id();

            $table->string('name', 32)->comment('退货人名称');
            $table->string('phone', 32)->comment('退货人电话');
            $table->string('province', 32)->comment('省');
            $table->string('city', 32)->comment('市');
            $table->string('district', 32)->comment('区');
            $table->string('address')->comment('详细地址');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('mall_order_refund_logistics', function (Blueprint $table) {
            $table->string('name', 32)->comment('退货人名称')->after('logistics_no');
            $table->string('phone', 32)->comment('退货人电话')->after('name');
            $table->string('province', 32)->comment('省')->after('phone');
            $table->string('city', 32)->comment('市')->after('province');
            $table->string('district', 32)->comment('区')->after('city');
            $table->string('address')->comment('详细地址')->after('district');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_address');
    }
};
