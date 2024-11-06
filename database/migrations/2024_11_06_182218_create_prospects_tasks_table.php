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
        Schema::create('prospects_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('assigned_to')->nullable(); // Make this nullable
            $table->string('assigned_by')->nullable(); // Make this nullable
            $table->date('start_date')->nullable(); // Make this nullable
            $table->date('due_date')->nullable(); // Make this nullable
            $table->string('priority')->nullable(); // Make this nullable
            $table->foreignId('prospect_id')->constrained()->onDelete('cascade'); // Foreign key constraint
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prospects_tasks');
    }
};

      
            
           

