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

        Schema::create('operation_log', function (Blueprint $table) {
            $table->id();

            $table->string('loggable_type', 100)->comment('关联类型');
            $table->foreignId('loggable_id')->comment('关联ID');
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
        //
    }
};
