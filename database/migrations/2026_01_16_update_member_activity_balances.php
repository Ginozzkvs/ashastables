<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update member_activity_balances to match the new membership_activity_limits
        
        // Horse riding: 12/year
        DB::table('member_activity_balances')
            ->where('activity_id', 1)
            ->update(['remaining_count' => 12]);

        // Entry ticket: 365/year
        DB::table('member_activity_balances')
            ->where('activity_id', 2)
            ->update(['remaining_count' => 365]);

        // Free drink: 365/year
        DB::table('member_activity_balances')
            ->where('activity_id', 3)
            ->update(['remaining_count' => 365]);

        // Discount 5% (Room): 365/year
        DB::table('member_activity_balances')
            ->where('activity_id', 4)
            ->update(['remaining_count' => 365]);
    }

    public function down(): void
    {
        // Revert to previous values
        DB::table('member_activity_balances')
            ->where('activity_id', 1)
            ->update(['remaining_count' => 24]);

        DB::table('member_activity_balances')
            ->where('activity_id', 2)
            ->update(['remaining_count' => 52]);

        DB::table('member_activity_balances')
            ->where('activity_id', 3)
            ->update(['remaining_count' => 24]);

        DB::table('member_activity_balances')
            ->where('activity_id', 4)
            ->update(['remaining_count' => 365]);
    }
};
