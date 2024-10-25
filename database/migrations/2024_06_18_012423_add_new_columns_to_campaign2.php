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
            $table->string('image')->after('esewa-status');
            $table->string('a')->after('image');
            $table->string('heading')->after('a');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaign2', function (Blueprint $table) {
            $table->dropColumn(['image', 'a', 'heading']);
        });
    }
};
