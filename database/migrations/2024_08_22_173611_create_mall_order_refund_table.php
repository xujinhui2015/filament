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
        Schema::create('mall_order_refund', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id');
            $table->string('refund_order_no', 100)->comment('退款订单号');
            $table->unsignedTinyInteger('refund_type')->comment('退款类型0仅退款1退货退款');
            $table->unsignedTinyInteger('refund_status')->comment('退款类型0申请退款1同意退款2买家退货3卖家确认收货4确认退款5退款成功6退款失败7退款关闭(仅退款只有014567)');
            $table->decimal('refund_money')->comment('退款金额');
            $table->string('phone', 32)->comment('退货人联系电话');

            $table->string('refund_reason', 500)->nullable()->comment('退款原因');
            $table->string('buyer_message', 500)->nullable()->comment('买家留言');
            $table->string('buyer_images', 1000)->nullable()->comment('买家图片凭证');
            $table->string('seller_message', 500)->nullable()->comment('卖家留言');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('mall_order_refund_logistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_refund_id');
            $table->string('logistics_company_name')->comment('物流公司名称');
            $table->string('logistics_no')->comment('快递单号');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('mall_order_refund_detail', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_refund_id');
            $table->foreignId('order_detail_id');
            $table->foreignId('goods_id');
            $table->foreignId('goods_sku_id');
            $table->unsignedMediumInteger('refund_number')->comment('退货数量');

            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
