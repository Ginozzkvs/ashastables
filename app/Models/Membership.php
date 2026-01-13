<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'duration_days',
    ];

    /* =====================
     | Relationships
     ===================== */

    // Members under this membership
    public function members()
    {
        return $this->hasMany(Member::class);
    }

    // Activity limits for this membership
    public function activityLimits()
    {
        return $this->hasMany(MembershipActivityLimit::class);
    }
}
