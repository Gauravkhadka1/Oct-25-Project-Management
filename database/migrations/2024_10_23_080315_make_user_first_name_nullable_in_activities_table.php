<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeUserFirstNameNullableInActivitiesTable extends Migration
{
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('user_first_name')->nullable()->change(); // Change the column to be nullable
        });
    }

    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('user_first_name')->nullable(false)->change(); // Revert back to not nullable if rolled back
        });
    }
}
