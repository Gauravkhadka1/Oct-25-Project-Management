<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientTasksTable extends Migration
{
    public function up()
    {
        Schema::create('client_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->string('name');
            $table->unsignedBigInteger('assigned_to'); // User ID
            $table->date('due_date');
            $table->enum('priority', ['Normal', 'High', 'Urgent']);
            $table->string('status')->default('Pending');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_tasks');
    }
}
