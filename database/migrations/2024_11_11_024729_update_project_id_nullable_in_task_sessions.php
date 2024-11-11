<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProjectIdNullableInTaskSessions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('task_sessions', function (Blueprint $table) {
            // Make the project_id column nullable
            $table->unsignedBigInteger('project_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('task_sessions', function (Blueprint $table) {
            // Revert back to not nullable if needed
            $table->unsignedBigInteger('project_id')->nullable(false)->change();
        });
    }
}
