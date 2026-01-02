<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/ActivityLog.php
class ActivityLog extends Model
{
    protected $fillable = [
        'staff_id',
        'member_id',
        'activity_id',
        'success',
        'message'
    ];

    public function staff() {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function member() {
        return $this->belongsTo(Member::class);
    }

    public function activity() {
        return $this->belongsTo(Activity::class);
    }
}

