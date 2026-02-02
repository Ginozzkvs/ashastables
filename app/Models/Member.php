<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Member extends Model
{
    use HasFactory;

    protected $primaryKey = 'card_id';
    public $incrementing = false;
    protected $keyType = 'string';

  protected $fillable = [
    'card_id',
    'name',
    'phone',
    'email',
    'card_uid',
    'membership_id',
    'start_date',
    'active',
    'expiry_date',
    'renewed_at',
  ];

  protected $casts = [
    'start_date' => 'datetime',
    'expiry_date' => 'datetime',
    'renewed_at' => 'datetime',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
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
        return $this->hasMany(MemberActivityBalance::class, 'member_id', 'card_id');
    }

    /* =====================
     | Helper Methods
     ===================== */
    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiring()
    {
        if (!$this->expiry_date) return false;
        $daysUntilExpiry = now()->diffInDays($this->expiry_date);
        return $daysUntilExpiry >= 0 && $daysUntilExpiry <= 30;
    }

    public function daysUntilExpiry()
    {
        if (!$this->expiry_date) return null;
        return now()->diffInDays($this->expiry_date);
    }

    /**
     * Sync activity balances with membership's activity limits.
     * Creates missing balances for new activities added to the membership.
     */
    public function syncActivityBalances()
    {
        $membership = $this->membership()->with('activityLimits')->first();

        if (!$membership || $membership->activityLimits->isEmpty()) {
            return;
        }

        // Get existing activity IDs for this member
        $existingActivityIds = $this->activityBalances()->pluck('activity_id')->toArray();

        // Create balances for any missing activities
        foreach ($membership->activityLimits as $limit) {
            if (!in_array($limit->activity_id, $existingActivityIds)) {
                MemberActivityBalance::create([
                    'member_id'        => $this->card_id,
                    'activity_id'      => $limit->activity_id,
                    'remaining_count'  => $limit->max_per_year,
                    'used_today'       => 0,
                    'last_used_date'   => null,
                ]);
            }
        }
    }

    public static function generateCardId()
    {
        $lastMember = self::latest('created_at')->first();
        $lastNumber = 0;

        if ($lastMember && $lastMember->card_id) {
            // Extract the numeric part from last card_id (e.g., "AS0001" -> 1)
            $lastNumber = (int) substr($lastMember->card_id, 2);
        }

        $newNumber = $lastNumber + 1;
        return 'AS' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
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
                    'member_id'        => $member->card_id,
                    'activity_id'      => $limit->activity_id,
                    'remaining_count'  => $limit->max_per_year,
                    'used_today'       => 0,
                    'last_used_date'   => null,
                ]);
            }
        });
    }
}
