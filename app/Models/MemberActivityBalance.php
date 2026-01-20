<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberActivityBalance extends Model
{
    protected $fillable = [
        'member_id',
        'activity_id',
        'remaining_count',
        'used_today',
        'last_used_date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'card_id');
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function membershipLimit()
    {
        return $this->belongsTo(MembershipActivityLimit::class, 'activity_id', 'activity_id');
    }
}
