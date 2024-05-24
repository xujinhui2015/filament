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
        Schema::create('mall_order_operation_log', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id');
            $table->foreignId('user_id')->nullable()->comment('操作人');

            $table->string('action')->comment('动作');
            $table->string('operation')->comment('操作说明');

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
