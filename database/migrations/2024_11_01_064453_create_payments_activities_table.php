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
        Schema::create('payments_activities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payments_id'); // Foreign key to the payments table
            $table->string('details'); // Column to store activity details
            $table->string('user_first_name')->nullable(); // Make this column nullable
            $table->timestamp('date')->useCurrent(); // Column to store the date of the activity
            $table->timestamps(); // Automatically manage created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments_activities');
    }
};
