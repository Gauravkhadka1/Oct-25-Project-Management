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
        Schema::create('project_tasks_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('task_id'); // Foreign key to the payments table
            $table->string('comments'); // Column to store activity details
            $table->string('username')->nullable(); // Make this column nullable
            $table->timestamp('date')->useCurrent(); // Column to store the date of the activity
            $table->timestamps(); // Automatically manage created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_tasks_comments');
    }
};
