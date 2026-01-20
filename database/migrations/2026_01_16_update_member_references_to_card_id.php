<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Update member_activity_balances to match new card_id values
        $balances = DB::table('member_activity_balances')->select('id', 'member_id')->get();
        foreach ($balances as $balance) {
            // Find the member's card_id from any matching record, or generate it
            $member = DB::table('members')->where(DB::raw("CAST(SUBSTRING(card_id, 3) AS UNSIGNED)"), $balance->member_id)->first();
            if ($member) {
                DB::table('member_activity_balances')
                    ->where('id', $balance->id)
                    ->update(['member_id' => $member->card_id]);
            }
        }

        // Update activity_logs
        $logs = DB::table('activity_logs')->select('id', 'member_id')->get();
        foreach ($logs as $log) {
            $member = DB::table('members')->where(DB::raw("CAST(SUBSTRING(card_id, 3) AS UNSIGNED)"), $log->member_id)->first();
            if ($member) {
                DB::table('activity_logs')
                    ->where('id', $log->id)
                    ->update(['member_id' => $member->card_id]);
            }
        }

        // Update activity_usages
        $usages = DB::table('activity_usages')->select('id', 'member_id')->get();
        foreach ($usages as $usage) {
            $member = DB::table('members')->where(DB::raw("CAST(SUBSTRING(card_id, 3) AS UNSIGNED)"), $usage->member_id)->first();
            if ($member) {
                DB::table('activity_usages')
                    ->where('id', $usage->id)
                    ->update(['member_id' => $member->card_id]);
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // This migration cannot be easily reversed
    }
};
