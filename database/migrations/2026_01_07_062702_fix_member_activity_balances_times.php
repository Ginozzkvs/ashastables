<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('member_activity_balances', function (Blueprint $table) {
            // remove old minute-based fields
            if (Schema::hasColumn('member_activity_balances', 'daily_minutes_limit')) {
                $table->dropColumn('daily_minutes_limit');
            }

            if (Schema::hasColumn('member_activity_balances', 'used_today_minutes')) {
                $table->dropColumn('used_today_minutes');
            }

            // add count-based fields
            $table->integer('daily_limit')->nullable()->after('remaining_count');
            $table->integer('used_today')->default(0)->after('daily_limit');
        });
    }

    public function down(): void
    {
        Schema::table('member_activity_balances', function (Blueprint $table) {
            // rollback to old structure
            $table->integer('daily_minutes_limit')->nullable();
            $table->integer('used_today_minutes')->default(0);

            $table->dropColumn(['daily_limit', 'used_today']);
        });
    }
};

