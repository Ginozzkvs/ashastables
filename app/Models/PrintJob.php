<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintJob extends Model
{
    protected $fillable = [
        'receipt_data',
        'status',
    ];

    protected $casts = [
        'receipt_data' => 'array',
    ];
}
