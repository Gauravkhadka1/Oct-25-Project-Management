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
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('elapsed_time')->default(0); // Store elapsed time in seconds
        });
    }
    
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('elapsed_time');
        });
    }
};
