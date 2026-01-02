<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Member extends Model
{
    protected $fillable = ['name', 'phone', 'qr_code', 'membership_id', 'start_date', 'end_date', 'active'];

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function activityBalances()
    {
        return $this->hasMany(MemberActivityBalance::class);
    }

    public static function boot()
    {
        parent::boot();

        // When a new member is created, auto-generate activity balances
        static::created(function ($member) {
            $membership = $member->membership;
            if ($membership) {
                foreach ($membership->activityLimits as $limit) {
                    MemberActivityBalance::create([
                        'member_id' => $member->id,
                        'activity_id' => $limit->activity_id,
                        'remaining_count' => $limit->max_per_year,
                        'daily_minutes_limit' => $limit->daily_minutes,
                        'used_today_minutes' => 0,
                        'last_used_date' => null
                    ]);
                }
            }
        });
    }
}
