<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Member;
use App\Models\MembershipActivityLimit;

return new class extends Migration
{
    public function up(): void
    {
        // Create activity balances for existing members that don't have them
        $members = Member::all();
        
        foreach ($members as $member) {
            $membership = $member->membership()->with('activityLimits')->first();
            
            if ($membership && !$membership->activityLimits->isEmpty()) {
                foreach ($membership->activityLimits as $limit) {
                    // Check if balance already exists
                    $exists = DB::table('member_activity_balances')
                        ->where('member_id', $member->card_id)
                        ->where('activity_id', $limit->activity_id)
                        ->exists();
                    
                    if (!$exists) {
                        DB::table('member_activity_balances')->insert([
                            'member_id' => $member->card_id,
                            'activity_id' => $limit->activity_id,
                            'remaining_count' => $limit->max_per_year,
                            'used_today' => 0,
                            'last_used_date' => null,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    public function down(): void
    {
        // This migration cannot be easily reversed
    }
};
