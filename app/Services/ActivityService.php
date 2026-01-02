<?php

namespace App\Services;

use App\Models\Member;
use Carbon\Carbon;
use Exception;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;


class ActivityService
{
      public static function useActivity($qrCode, $activityId, $minutes)
      {
            $member = Member::where('qr_code', $qrCode)->firstOrFail();

            if (!$member->active || now()->gt($member->end_date)) {
                  throw new Exception('Membership expired or inactive');
            }

            $balance = $member->activityBalances()
                  ->where('activity_id', $activityId)
                  ->first();

            // Create balance if doesn't exist
            if (!$balance) {
                  $balance = $member->activityBalances()->create([
                        'activity_id' => $activityId,
                        'remaining_count' => 10, // Default limit
                        'daily_minutes_limit' => 60, // Default daily limit
                        'used_today_minutes' => 0,
                        'last_used_date' => null
                  ]);
            }

            // Reset daily usage if first use or new day
            if (
                  is_null($balance->last_used_date) ||
                  $balance->last_used_date !== now()->toDateString()
            ) {
                  $balance->used_today_minutes = 0;
                  $balance->last_used_date = now()->toDateString();
            } else {
                  // Already used today
                  throw new Exception('Can only use once per day');
            }


            // Daily minutes check
            if (($balance->used_today_minutes + $minutes) > $balance->daily_minutes_limit) {
                  throw new Exception('Daily limit exceeded');
            }

            // Update balance
            $balance->used_today_minutes += $minutes;
            $balance->remaining_count -= 1;
            $balance->last_used_date = now()->toDateString();
            $balance->save();

            ActivityLog::create([
                  'staff_id' => Auth::id(),
                  'member_id' => $member->id,
                  'activity_id' => $activityId,
                  'success' => true,
                  'message' => 'Activity used successfully'
            ]);

            return 'Activity approved';
      }
}
