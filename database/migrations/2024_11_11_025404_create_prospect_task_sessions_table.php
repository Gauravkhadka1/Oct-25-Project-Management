<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('prospect_task_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prospect_task_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('started_at');
            $table->timestamp('paused_at')->nullable();
            $table->unsignedBigInteger('elapsed_time')->default(0); // Store elapsed time in seconds
            $table->timestamps();
    
            // Add foreign key constraint
            $table->foreign('prospect_task_id')->references('id')->on('prospect_tasks')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('prospect_task_sessions');
    }
    
};
