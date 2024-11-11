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
    Schema::table('prospect_task_sessions', function (Blueprint $table) {
        $table->unsignedBigInteger('prospect_task_id')->nullable()->after('prospect_id');
    });

    Schema::table('payment_task_sessions', function (Blueprint $table) {
        $table->unsignedBigInteger('payment_task_id')->nullable()->after('payments_id');
    });
}

public function down()
{
    Schema::table('prospect_task_sessions', function (Blueprint $table) {
        $table->dropColumn('prospect_task_id');
    });

    Schema::table('payment_task_sessions', function (Blueprint $table) {
        $table->dropColumn('payment_task_id');
    });
}
};
