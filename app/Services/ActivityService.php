<?php

namespace App\Services;

use App\Models\Member;
use App\Models\MembershipActivityLimit;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Exception;

class ActivityService
{
    public static function useActivity($cardUid, $activityId, $minutes = 1)
    {
        // 1️⃣ Find member
        $member = Member::where('card_uid', $cardUid)->firstOrFail();

        if (!$member->active || ($member->expiry_date && now()->gt($member->expiry_date))) {
            throw new Exception('Membership expired or inactive');
        }

        // 2️⃣ Get activity balance
        $balance = $member->activityBalances()
            ->where('activity_id', $activityId)
            ->first();

        if (!$balance) {
            throw new Exception('No activity balance found');
        }

        // 3️⃣ Get membership limits
        $limit = MembershipActivityLimit::where('membership_id', $member->membership_id)
            ->where('activity_id', $activityId)
            ->first();

        if (!$limit) {
            throw new Exception('Activity not allowed for this membership');
        }

        // 4️⃣ Reset daily usage if new day
        if ($balance->last_used_date !== now()->toDateString()) {
            $balance->used_today = 0;
            $balance->last_used_date = now()->toDateString();
        }

        // 5️⃣ Daily limit check
        if ($limit->max_per_day !== null) {
            if (($balance->used_today + $minutes) > $limit->max_per_day) {
                throw new Exception('Daily limit exceeded');
            }
        }

        // 6️⃣ Total remaining check
        if ($balance->remaining_count <= 0) {
            throw new Exception('No remaining usage');
        }

        // 7️⃣ Apply usage
        $balance->used_today += $minutes;
        $balance->remaining_count -= 1;
        $balance->save();
	
        $user = auth()->user();
        // 8️⃣ Log activity
        $activityLog = ActivityLog::create([
	    'user_id'     => $user?->id,
	    'user_role'   => $user?->role ?? 'staff',
            'member_id'   => $member->card_id,
	    'card_uid'    => $cardUid,
            'activity_id' => $activityId,
            'success'     => true,
            'message'     => 'Activity used successfully',
        ]);

        return $activityLog;
    }
}
