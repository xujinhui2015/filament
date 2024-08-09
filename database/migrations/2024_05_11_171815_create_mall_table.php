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
        Schema::create('mall_goods', function (Blueprint $table) {
            $table->id();

            $table->string('goods_sn', 100)->comment('商品代码');
            $table->foreignId('goods_category_id')->comment('商品分类ID');
            $table->string('goods_name', 100)->comment('商品名称');
            $table->string('subtitle', 100)->comment('商品副标题');
            $table->string('main_img')->comment('商品主图');
            $table->string('images', 1000)->comment('商品轮播图');
            $table->longText('content')->comment('商品详情');
            $table->boolean('is_sale')->default(false)->comment('是否上架0否1是');

            $table->comment('商城商品表');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('mall_goods_category', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_id');
            $table->string('title', 100)->comment('分类名称');

            $table->boolean('is_disabled')->default(false);
            $table->unsignedInteger('sort')->default(0);

            $table->comment('商城商品分类表');

            $table->softDeletes();
            $table->timestamps();
        });



        Schema::create('mall_attr', function (Blueprint $table) {
            $table->id();

            $table->string('attr_name', 100)->comment('规格名称');

            $table->boolean('is_disabled')->default(false);
            $table->unsignedInteger('sort')->default(0);

            $table->comment('商城商品规格表');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('mall_attr_value', function (Blueprint $table) {
            $table->id();

            $table->string('attr_id');
            $table->string('attr_value_name', 100)->comment('规格值名称');

            $table->boolean('is_disabled')->default(false);
            $table->unsignedInteger('sort')->default(0);

            $table->comment('商城商品规格值表');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('mall_goods_attr', function (Blueprint $table) {
            $table->id();

            $table->foreignId('goods_id')->comment('商品ID');
            $table->string('attr_name', 100)->comment('规格名称');

            $table->boolean('is_disabled')->default(false);
            $table->unsignedInteger('sort')->default(0);

            $table->comment('商城商品规格属性表');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('mall_goods_attr_value', function (Blueprint $table) {
            $table->id();

            $table->foreignId('goods_attr_id')->comment('商城商品规格表ID');
            $table->string('attr_value_name', 100)->comment('商品规格值名称');

            $table->boolean('is_disabled')->default(false);
            $table->unsignedInteger('sort')->default(0);

            $table->comment('商城商品规格属性值表');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('mall_goods_sku', function (Blueprint $table) {
            $table->id();

            $table->foreignId('goods_id');
            $table->string('spec', 500)->comment('规格明细');
            $table->unsignedInteger('price')->comment('单价');
            $table->string('sku_img')->nullable()->comment('规格图片');
            $table->unsignedMediumInteger('stock')->comment('库存');

            $table->comment('商城Sku表');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('mall_order', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id');
            $table->string('order_no', 100)->comment('订单号');
            $table->unsignedTinyInteger('order_status')->comment('订单状态0待付款1待发货2待收货3退款处理4已完成5已关闭6锁单状态');
            $table->unsignedInteger('order_money')->comment('订单金额');
            $table->unsignedInteger('order_fact_money')->comment('订单实付金额');
            $table->unsignedTinyInteger('order_source')->comment('订单来源0直接下单1购物车');
            $table->unsignedTinyInteger('payment')->nullable()->comment('支付方式1余额支付2微信支付');

            $table->string('name', 32)->comment('收货人姓名');
            $table->string('phone', 32)->comment('收货人电话');
            $table->string('province', 32)->comment('省');
            $table->string('city', 32)->comment('市');
            $table->string('district', 32)->comment('区');
            $table->string('address')->comment('详细地址');

            $table->dateTime('last_pay_time')->nullable()->comment('最后付款时间');
            $table->dateTime('pay_time')->nullable()->comment('付款时间');
            $table->dateTime('delivery_time')->nullable()->comment('发货时间');
            $table->dateTime('finish_time')->nullable()->comment('完成时间');
            $table->dateTime('cancel_time')->nullable()->comment('取消时间');
            $table->dateTime('turnoff_time')->nullable()->comment('关闭时间');

            $table->string('logistics_name', 64)->nullable()->comment('物流公司名称');
            $table->string('logistics_no', 128)->nullable()->comment('物流单号');
            $table->string('buyer_remark')->nullable()->comment('买家留言');
            $table->string('seller_message')->nullable()->comment('卖家留言');
            $table->string('prepay_id', 64)->nullable()->comment('微信支付ID');

            $table->comment('商城订单表');


            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('mall_order_detail', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id');
            $table->foreignId('goods_id');
            $table->foreignId('goods_sku_id');
            $table->string('goods_name')->comment('商品名称');
            $table->string('goods_spec')->comment('商品规格');
            $table->string('goods_image')->comment('商品图片');
            $table->unsignedInteger('goods_price')->comment('商品价格');
            $table->unsignedMediumInteger('goods_number')->comment('购买商品数量');

            $table->comment('商城订单详情表');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('mall_order_adjust', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id');
            $table->foreignId('order_detail_id');
            $table->string('adjust_type', 32)->comment('调整类型');
            $table->integer('adjust_price')->comment('调整价格');

            $table->comment('商城订单价格调整表');

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
