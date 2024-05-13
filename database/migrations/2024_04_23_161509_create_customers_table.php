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
        Schema::create('customer', function (Blueprint $table) {
            $table->id();

            $table->string('nickname', 32)->nullable()->comment('昵称');
            $table->string('avatar_url')->nullable()->comment('头像');
            $table->char('phone', 11)->nullable()->comment('手机号');
            $table->integer('balance')->default(0)->comment('余额');
            $table->integer('points')->default(0)->comment('积分');

            $table->comment('会员表');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('customer_wechat', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->comment('会员ID');
            $table->string('mini_openid', 128)->comment('小程序openid');

            $table->comment('会员微信表');

            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('customer_points_record', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->comment('会员ID');
            $table->string('change_explain')->comment('说明');
            $table->string('scene_type', 32)->comment('场景类型');
            $table->integer('points')->comment('变动积分');
            $table->foreignId('relation_id')->comment('关联ID');
            $table->string('remark')->nullable()->comment('备注');

            $table->comment('会员积分记录表');

            $table->timestamp('created_at')->nullable();
        });

        Schema::create('customer_balance_record', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->comment('会员ID');
            $table->string('change_explain')->comment('说明');
            $table->string('scene_type', 32)->comment('场景类型');
            $table->integer('balance')->comment('变动金额');
            $table->foreignId('relation_id')->comment('关联ID');
            $table->string('remark')->nullable()->comment('备注');

            $table->comment('会员金额记录表');

            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
