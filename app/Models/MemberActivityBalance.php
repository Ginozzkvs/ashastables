<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberActivityBalance extends Model
{
    protected $fillable = ['member_id', 'activity_id', 'remaining_count', 'daily_minutes_limit', 'used_today_minutes', 'last_used_date'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
