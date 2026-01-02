<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = ['name', 'price', 'duration_days'];

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function activityLimits()
    {
        return $this->hasMany(MembershipActivityLimit::class);
    }
}
