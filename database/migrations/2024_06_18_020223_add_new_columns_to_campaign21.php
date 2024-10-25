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
        Schema::table('campaign2', function (Blueprint $table) {
            $table->string('total-raised')->after('a');
            $table->string('progress-percentage')->after('total-raised');
            $table->string('raised-percentage')->after('progress-percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign2', function (Blueprint $table) {
            $table->dropColumn(['total-raised', 'progress-percentage', 'raised-percentage']);
        });
    }
};
