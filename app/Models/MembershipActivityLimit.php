<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipActivityLimit extends Model
{
    protected $fillable = ['membership_id', 'activity_id', 'max_per_year', 'daily_minutes'];

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
