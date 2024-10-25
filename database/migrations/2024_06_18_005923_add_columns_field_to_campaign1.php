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
        Schema::table('campaign1', function (Blueprint $table) {
            $table->string('image')->after('tip');
           $table->string('a')->after('tip');
           $table->string('heading')->after('tip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign1', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('a');
            $table->dropColumn('heading');
        });
    }
};
