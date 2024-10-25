<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('prospect_id'); // Foreign key to the prospects table
            $table->string('details'); // Column to store activity details
            $table->string('user_first_name')->nullable(); // Make this column nullable
            $table->timestamp('date')->useCurrent(); // Column to store the date of the activity
            $table->timestamps(); // Automatically manage created_at and updated_at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
