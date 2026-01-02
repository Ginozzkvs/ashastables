<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['name', 'unit'];

    public function limits()
    {
        return $this->hasMany(MembershipActivityLimit::class);
    }
}
