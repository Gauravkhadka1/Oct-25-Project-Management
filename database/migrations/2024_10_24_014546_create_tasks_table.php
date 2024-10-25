<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('assigned_to')->nullable(); // Make this nullable
            $table->string('assigned_by')->nullable(); // Make this nullable
            $table->date('start_date')->nullable(); // Make this nullable
            $table->date('due_date')->nullable(); // Make this nullable
            $table->string('priority')->nullable(); // Make this nullable
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // Foreign key constraint
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
