<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Member extends Model
{
    use HasFactory;

  protected $fillable = [
    'name',
    'phone',
    'email',
    'card_uid',
    'membership_id',
    'start_date',
    'end_date',
    'active',

];

    /* =====================
     | Relationships
     ===================== */
    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function activityBalances()
    {
        return $this->hasMany(MemberActivityBalance::class);
    }

    /* =====================
     | Model Events
     ===================== */
    protected static function booted()
    {
        static::created(function ($member) {

            // Load membership + limits safely
            $membership = $member->membership()->with('activityLimits')->first();

            if (!$membership || $membership->activityLimits->isEmpty()) {
                return; // No limits → no balances
            }

            foreach ($membership->activityLimits as $limit) {
                MemberActivityBalance::create([
                    'member_id'        => $member->id,
                    'activity_id'      => $limit->activity_id,
                    'remaining_count'  => $limit->max_per_year,
                    'used_today'       => 0,
                    'last_used_date'   => null,
                ]);
            }
        });
    }
}
