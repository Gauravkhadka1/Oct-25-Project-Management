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
    Schema::table('clients', function (Blueprint $table) {
        $table->foreignId('category_id')->nullable()->constrained('categories');
        $table->foreignId('subcategory_id')->nullable()->constrained('subcategories');
        $table->foreignId('additional_subcategory_id')->nullable()->constrained('additional_subcategories');
    });
}

public function down(): void
{
    Schema::table('clients', function (Blueprint $table) {
        $table->dropForeign(['category_id']);
        $table->dropForeign(['subcategory_id']);
        $table->dropForeign(['additional_subcategory_id']);
        $table->dropColumn(['category_id', 'subcategory_id', 'additional_subcategory_id']);
    });
}

};
