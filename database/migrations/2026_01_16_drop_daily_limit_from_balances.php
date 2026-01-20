<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('member_activity_balances', function (Blueprint $table) {
            $table->dropColumn('daily_limit');
        });
    }

    public function down(): void
    {
        Schema::table('member_activity_balances', function (Blueprint $table) {
            $table->integer('daily_limit')->default(60)->after('remaining_count');
        });
    }
};
