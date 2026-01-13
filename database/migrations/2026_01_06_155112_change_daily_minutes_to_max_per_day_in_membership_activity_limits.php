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
    Schema::table('membership_activity_limits', function (Blueprint $table) {
        $table->dropColumn('daily_minutes');
        $table->integer('max_per_day')->nullable()->after('max_per_year');
    });
}

public function down()
{
    Schema::table('membership_activity_limits', function (Blueprint $table) {
        $table->dropColumn('max_per_day');
        $table->integer('daily_minutes')->nullable();
    });
}

};
