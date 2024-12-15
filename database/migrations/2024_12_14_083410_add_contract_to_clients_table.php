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
        Schema::table('clients', function (Blueprint $table) {
            $table->string('contract')->nullable()->after('contact_person_email'); // Add contract column
        });
    }
    
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('contract'); // Remove contract column
        });
    }
    
};
