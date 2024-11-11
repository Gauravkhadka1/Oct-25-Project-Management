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
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('prospect_task_id')->constrained()->onDelete('cascade'); // Foreign key for ProspectTask
        $table->foreignId('prospect_id')->constrained()->onDelete('cascade'); // Foreign key for Prospect
        $table->timestamp('started_at')->nullable();
        $table->timestamp('paused_at')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('prospect_task_sessions');
}

};
