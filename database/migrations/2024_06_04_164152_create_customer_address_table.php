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
        Schema::create('customer_address', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->comment('会员ID');

            $table->string('name', 32)->comment('姓名');
            $table->string('phone', 32)->comment('电话');
            $table->string('province', 32)->comment('省');
            $table->string('city', 32)->comment('市');
            $table->string('district', 32)->comment('区');
            $table->string('address')->comment('详细地址');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_address');
    }
};
